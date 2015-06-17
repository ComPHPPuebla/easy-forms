# Creating forms

The simplest way to create a form is inheriting from `EasyForms\Form` class.
Elements can be added within the constructor.

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
$interests->isValid; // false if there's at least 1 error message
$interests->messages; // validation messages, if any
$interests->choices; // empty if the element is not a subclass of Choice
$interests->isSelected('testing') // Helper method for elements with choices
```
