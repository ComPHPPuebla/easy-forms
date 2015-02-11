# Dynamic modification

Most of the times your forms will need to be populated with information from a
database, or they will need to add/remove/modify elements and validators depending
on the values provided by the user.

For this kind of tasks Symfony2 uses events, Zend form uses its [Hydrator][8]
package. This package offers no mechanisms to do these tasks, so you can use
whatever you need or prefer.

Suppose you have a form to add products to a shopping cart in an e-commerce
application.

```php
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Form;

class AddToCartForm extends Form
{
    public function __construct()
    {
        $this
            ->add(new Select('product'))
            ->add(new Text('quantity'))
        ;
    }
}
```

You will want to populate the `select` element choices with the products from
your database. We will use a configuration object to populate our form element.
In this example the `ProductsCatalog` class is a repository and the
`ProductInformation` class is a DTO.

```php
class AddToCartConfiguration
{
    protected $catalog;

    public function __construct(ProductsCatalog $catalog)
    {
        $this->catalog = $catalog;
    }

    public function getProductOptions()
    {
        $options = [];
        array_map(function (ProductInformation $product) use (&$options) {
            $options[$product->id] = "{$product->name}, \${$product->price}";
        }, $this->catalog->all());

        return $options;
    }
}
```

Then we would have to add a method to our form to use the information provided by
this configuration object.

```php
use EasyForms\Elements\Select;

class AddToCartForm extends Form
{
    /* ... */

    public function configure(AddToCartConfiguration $configuration)
    {
        /** @var Select $product */
        $product = $this->get('product');

        $product->setChoices($configuration->getProductOptions());
    }
}
```

We would configure our form the following way:

```php
$addToCartForm = new AddToCartForm();
$addToCartForm->configure(new AddToCartConfiguration(new ProductsCatalog()));
```

We would have to update this form's validator dynamically too, in order to
pass the valid products that a user can sent through this form to it.

```php
use Zend\Filter\Int;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class AddToCartFilter extends InputFilter
{
    public function __construct()
    {
        $this->add($this->buildProductInput());
        $this->add($this->buildQuantityInput());
    }

    public function configure(AddToCartConfiguration $configuration)
    {
        $product = $this->get('product');
        $product
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => $configuration->getProductsIds(),
            ]))
        ;
    }

    protected function buildProductInput()
    {
        $product = new Input('product');
        $product
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;
        $product
            ->getFilterChain()
            ->attach(new Int())
        ;
        return $product;
    }

    protected function buildQuantityInput()
    {
        $quantity = new Input('quantity');
        $quantity
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new Digits())
        ;
        $quantity
            ->getFilterChain()
            ->attach(new Int())
        ;
        return $quantity;
    }
}
```

The configuration object would have to get the valid products IDs that the `InArray`
validator will use.

```php
class AddToCartConfiguration
{
    /* ... */

    public function getProductsIds()
    {
        return array_keys($this->getProductOptions());
    }
}
```

Then we could configure the filter as follows:

```php
$filter = new AddToCartFilter();
$filter->configure(new AddToCartConfiguration(new ProductsCatalog()));
```

As you have seen through the examples, forms do not need to know how to modify
themselves dynamically, reason why this library does not provide any mechanisms
for such tasks.

[8]: http://framework.zend.com/manual/current/en/modules/zend.stdlib.hydrator.html
