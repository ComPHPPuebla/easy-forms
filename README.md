# Easy form handling for PHP

[![Build Status](https://travis-ci.org/ComPHPPuebla/easy-forms.svg?branch=master)](https://travis-ci.org/ComPHPPuebla/easy-forms)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b21b5fac-a347-4c1a-b9b0-9965a93893ea/mini.png)](https://insight.sensiolabs.com/projects/b21b5fac-a347-4c1a-b9b0-9965a93893ea)

This is a light library to process HTML forms in Web applications. Its main
goal is to delegate concerns like, validation, rendering, translation, and
dynamic modification/population to well-known components.

This package is intended to be used as a glue for packages that provide the
following functionality.

* Rendering ([Twig][3])
* Validation ([Zend input filter][4])
* Special form elements like CSRF tokens ([Symfony Security CSRF][5]), and
Captcha elements ([Zend CAPTCHA][6]).

All these dependencies are *optional* and more adapters can be added to
provide the same functionality with other components.

The reason behind this package is that most popular PHP packages (like
[Symfony][1] and [Zend][2]) require you to install several dependencies,
like translation, validation, and event dispatching packages, even if you
don't need to use them.

If you are already using a form component as part of its corresponding framework,
you probably will not find this package very useful. This package is intended to
be used when you need a simple integration and you don't need or want to install
more packages than you already have.

## Contents

* [Installation](#installation)
* [Usage](#usage)
    * [Form elements](#form-elements)
    * [Creating forms](#creating-forms)
    * [Validating forms](#validating-forms)
    * [Rendering forms](#rendering-forms)
    * [Special form elements](#special-form-elements)
        * [CSRF tokens](#csrf-tokens)
        * [CAPTCHA elements](#captcha-elements)
    * [Dynamic modification](#dynamic-modification)
    * [More examples](#more-examples)
* [TODO](#todo)

## Installation

Using Composer

```bash
$ composer require comphppuebla/easy-forms:~1.0@dev
```

## Usage

### Form elements

The only required attribute for an element in this package is its name, and
an optional set of choices.

```php
use EasyForms\Elements;

$name = new Elements\Text('name');
$password = new Elements\Password('password');
$userId = new Elements\Hidden('user_id');
$description = new Elements\TextArea('description');
$avatar = new Elements\File('avatar');
$termsAndConditions = new Elements\Checkbox('terms', $checkedValue = 'accept');
$gender = new Elements\Radio('gender', $choices = [
    'M' => 'Male', 'F' => 'Female'
]);
$position = new Elements\Select('position', $choices = [
    'b'=> 'Backend developer', 'f' => 'Frontend Developer'
]);
$interests = new Elements\MultiCheckbox('interests', $choices = [
    'u' => 'Usability', 's' => 'Security', 't' => 'Testing'
]);
```

Choices can be omitted in the constructor of elements like `Radio`, `Select`,
and `MultiCheckbox`, and injected later via the `setChoices` method.

```php
$gender->setChoices([
    'M' => 'Male', 'F' => 'Female'
]);
$position->setChoices([
    'b'=> 'Backend developer', 'f' => 'Frontend Developer'
]);
$interests->setChoices([
    'u' => 'Usability', 's' => 'Security', 't' => 'Testing'
]);
```

You can also make elements optional, and pass them error messages.

```php
$description->makeOptional();
$password->setMessages(['Please enter your password']);
```

Unlike other form components, elements in this package are not responsible of
validation and filtering.

### Creating forms

The simplest way to create a form is inheriting from `EasyForms\Form` and add
elements to it in its constructor.

```php
class LoginForm extends EasyForms\Form
{
    public function __construct()
    {
        $this
            ->add(new EasyForms\Elements\Text('username'))
            ->add(new EasyForms\Elements\Password('password'))
        ;
    }
}
```

However, you could simple create an `EasyForms\Form` object and start adding
elements.

```php
$loginForm = new EasyForms\Form();
$loginForm
    ->add(new EasyForms\Elements\Text('username'))
    ->add(new EasyForms\Elements\Password('password'))
;
```

If you add a `File` element, the form will update its `enctype` attribute to
`multipart/form-data` automatically.

Notice that when you create a form you only need to know its elements names,
and its types. You need the names in order to know how to retrieve its values,
from `$_GET`, `$_POST` and `$_FILES`. You need its types in order to know
whether an element can have multiple values or not (its value is either a
string or an array).

Once you have the form you can populate its values with any of the
super-globals, populate its error messages, if needed, and pass it to your
template engine.

```php
$loginForm->submit(array_merge($_POST, $_FILES));
$loginForm->setErrorMessages($errors);
$view->render('your-template.html', ['form' => $loginForm->buildView()]);
```

Both elements and forms have a view representation that exposes the attributes
that the templates need to render them.

```php
$form->attributes; // Form's HTML attributes
$interests = $form->interests; // Form elements can be accessed through its name
$interests->attributes; // Element's HTML attributes
$interests->value;
$interests->isRequired;
$interests->isValid; // true if there's at least 1 error message
$interests->messages; // validation messages
$interests->choices; // empty if the element is not a subclass of Choice
$interests->isSelected('testing') // Helper method for elements with choices
```

### Validating forms

Another concern related to forms is validation, you could keep on validating and
sanitizing your form inputs without having to couple validation components to your
form.

If you are already using the Zend input filter package, you could validate your
inputs and pass the error messages to your form so they can be rendered. Suppose
you have this filter to validate your form.

```php
use Zend\Filter\StringTrim;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Regex;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $this
            ->add($this->buildUsernameInput())
            ->add($this->buildPasswordInput())
        ;
    }

    protected function buildUsernameInput()
    {
        $username = new Input('username');
        $username
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength([
                'min' => 3,
            ]))
        ;
        $username
            ->getFilterChain()
            ->attach(new StringTrim())
        ;
        return $username;
    }

    protected function buildPasswordInput()
    {
        $password = new Input('password');
        $password
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength([
                'min' => 8,
            ]))
        ;
        return $password;
    }
}
```

Then, you could validate your form this way:

```php
$form = new LoginForm();
$filter = new LoginFilter();

$filter->setData($_POST);
if (!$filter->isValid() {
    $form->setErrorMessages($filter->getMessages());
}
$form->submit($filter->getValues());
```

And the form would not be aware of the library that performs the validation. This
package provides an optional integration in order to encapsulate the behaviour to
validate form inputs with a Zend input filter.

```php
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;

$form = new LoginForm();
$validator = new InputFilterValidator(new LoginFilter());

$form->submit($_POST);

// Form would have the errors messages, if needed, and its values would be filtered
$validator->validate($form);
```

In order to use a different component for validation you would need to write an
adapter that implements the `EasyForms\Validation\FormValidator` interface.

### Rendering forms

This package uses Twig and is heavily inspired by the way the Symfony2 form
component renders a form. Form rendering can be customized through *themes*. A
theme is a set of Twig templates which contain *blocks* to customize the way a
form element is rendered.

A form element is rendered in three parts:

* A label
* The HTML element
* Its error messages, if any

This package has 2 built-in themes:

* The default layout, which groups form elements in divs
* The Bootstrap 3 layout which extends the default layout

In order to use the themes you have to register the `FormExtension` provided in
this package.

```php
use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;

$loader = new Twig_Loader([
    // Path to themes
    'vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig',
    'path/to/your/application/templates',
]);
$twig = new Twig_Environment($loader);
// use the Bootstrap 3 layout
$renderer = new FormRenderer(
    new FormTheme($twig, 'layouts/bootstrap.html.twig'),
    new BlockOptions()
);
$twig->addExtension(new FormExtension($renderer));
```
Once you have the form extension configured, you can pass your form to any Twig
template.

```php
$view->render('user/login.html.twig', [
    'form' => $loginForm->buildView(),
]);
```

The extension defines some functions, among the most important are `form_start`,
`form_end`, and `element_row`.

The `element_row` defines 2 parameters, the form element, and an associative array
of options. The options are not mandatory, and can be explained as follows:

* `label`. The element 's label
* `label_attr`. The label's HTML attributes
* `attr`. The elements HTML  attributes
* `options`. This one is used mainly to override the default blocks that render the
elements (label, element and error messages, among others).

In the following example we use the options to define the elements labels, and its
HTML IDs which could be used for client side validation, for instance.

```twig
{{ form_start(login) }}
{{ element_row(login.username, {'label': 'Username', 'attr': {'id': 'username'}}) }}
{{ element_row(login.password, {'label': 'Password', 'attr': {'id': 'password'}}) }}
<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-home"></span> Login
</button>
{{ form_end() }}
```

If you want to display a form element in a different way in only one template, you
need to do 3 things:

1. Add the template that renders the form to the current theme
2. Define a custom block for your element
3. Override the block in the `options` parameter when calling `element_row`

Suppose you have a `ProductForm` class with 3 form elements, a text element with the
name of the product, a text area with an optional description, and another text
element to enter a unit price. To add the current template to the theme you will need
to use the `form_theme` token and pass the value `self` as argument, you can add more
than one template this way, therefore the value should be inside an array.

```twig
{# Add this template to the theme #}
{% form_theme [_self] %}

{# Custom block #}
{%- block money -%}
    <div class="input-group"><div class="input-group-addon">$</div>
        {%- set options = options|merge({'block': 'input'}) -%}
        {{- element(element, attr, options) -}}
    <div class="input-group-addon">.00</div></div>
{%- endblock money -%}

{{ form_start(form) }}
{{ element_row(form.name, {'label': 'Name', 'attr': {'id': 'name'}}) }}

{# Override the element's default block, use 'money' instead #}
{{ element_row(form.unitPrice, {'label': 'Price', 'options': {'block': 'money'}}) }}

<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-th-list"></span> Add to catalog
</button>
{{ form_end() }}
```

### Special form elements

#### CSRF tokens

In order to protect your application from Cross Site Request Forgery (CSRF) attacks
you can generate and validate secret tokens and include them in your forms. This
package integrates the Symfony Security CSRF package to do that task.

Let's add a CSRF token to the `LoginForm` used in previous examples.

```php
use EasyForms\Elements\Csrf\TokenProvider;
use EasyForms\Elements\Text;
use EasyForms\Elements\Password;
use EasyForms\Elements\Csrf;
use EasyForms\Form;

class LoginForm extends Form
{
    public function __construct(TokenProvider $csrfTokenProvider)
    {
        $this
            ->add(new Text('username'))
            ->add(new Password('password'))
            ->add(new Csrf('csrf_token', '_login_csrf_token', $csrfTokenProvider))
        ;
    }
}
```

A `Csrf` form element requires a name (as any element) an identifier for the token
and a `TokenProvider` object. We can configure a Symfony2 token provider as follows:

```php
use EasyForms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;


$loginform = new LoginForm(new CsrfTokenProvider(
   new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
));
```

You could validate the token adding a validator to the `LoginFilter` object showed in
previous examples.

```php
use EasyForms\Bridges\Zend\InputFilter\Validator\CsrfValidator;
use EasyForms\Elements\Csrf\TokenProvider;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
    public function __construct(TokenProvider $tokenProvider)
    {
        $this
            /* ... */
            ->add($this->buildCsrfInput($tokenProvider))
        ;
    }

    /* ... */

    protected function buildCsrfInput(TokenProvider $tokenProvider)
    {
        $csrf = new Input('csrf_token');
        $csrf->setContinueIfEmpty(true);
        $csrf
            ->getValidatorChain()
            ->attach(new CsrfValidator([
                'tokenProvider' => $tokenProvider,
                'tokenId' => '_login_csrf_token',
                'updateToken' => true,
            ]))
        ;

        return $csrf;
    }
}
```

Now you could validate your form as usual. Note that this form element does not
require special treatment in the template since it is a simple `hidden` element.

#### CAPTCHA elements

This package allows you to add CAPTCHA elements to your forms using the Zend
CAPTCHA package.

Suppose you have a form to post comments to a Blog that you want to protect against
SPAM. The form could look like the following:

```php
use EasyForms\Elements\Captcha;
use EasyForms\Elements\Captcha\CaptchaAdapter;
use EasyForms\Elements\TextArea;
use EasyForms\Form;

class CommentForm extends Form
{
    public function __construct(CaptchaAdapter $adapter)
    {
        $this
            ->add(new TextArea('message'))
            ->add(new Captcha('captcha', $adapter))
        ;
    }
}
```

Similar to the way a CSRF token element is configured, a CAPTCHA element requires
an adapter. The current implementation allows you to use images as challenges and
the ReCAPTCHA service. Let's create a ReCAPTCHA adapter in our example.

```php
use EasyForms\Bridges\Zend\Captcha\ReCaptchaAdapter;
use Zend\Captcha\ReCaptcha;
use Zend\Http\Client;
use ZendService\ReCaptcha\ReCaptcha as ReCaptchaService;

$reCaptcha = new ReCaptchaAdapter(new ReCaptcha([
    'service' => new ReCaptchaService(
        'xxx',
        'yyy',
        $params = null,
        $options = null,
        $ip = null,
        new Client($uri = null, ['adapter' => new Client\Adapter\Curl()])
    )
]));
$commentForm = new CommentForm($reCaptcha));
```

In order to render the captcha we have to add a new template to the current theme.
Let's assume we are using the Bootstrap 3 theme

```twig
{% form_theme ['layouts/captcha-bootstrap3.html.twig'] %}

{{ form_start(comment) }}
{{ element_row(comment.message, {'label': 'Share your opinion'}) }}

{{ element_row(comment.captcha, {'label': 'Type the words in the image below'}) }}

<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-comment"></span> Comment
</button>
{{ form_end() }}
```

The blocks for this element are not included by default in the main themes since
not all of your forms will need a CAPTCHA element.

### Dynamic modification

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

```
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

### More examples

You can find some working examples in a small Slim application in this
[repository][7].

## TODO

* Provide alternative implementations, for instance: validation with the Symfony
2 validation component.

[1]: http://symfony.com/doc/current/components/form/introduction.html
[2]: http://framework.zend.com/manual/current/en/modules/zend.form.intro.html
[3]: http://twig.sensiolabs.org/
[4]: http://framework.zend.com/manual/current/en/modules/zend.input-filter.intro.html
[5]: https://github.com/symfony/security-csrf
[6]: http://framework.zend.com/manual/current/en/modules/zend.captcha.intro.html
[7]: https://github.com/MontealegreLuis/easy-forms-examples
[8]: http://framework.zend.com/manual/current/en/modules/zend.stdlib.hydrator.html
