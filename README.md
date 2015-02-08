# Easy form handling for PHP

[![Build Status](https://travis-ci.org/ComPHPPuebla/easy-forms.svg?branch=master)](https://travis-ci.org/ComPHPPuebla/easy-forms)

Most popular PHP packages (like [Symfony][1] and [Zend][2]) provide support for
the following tasks related to forms processing.

* Rendering
* Validation
* Translation

However both packages have several dependencies, even for the simplest case
(internationalization, validation, and event dispatching packages for instance).
For form rendering both packages need to use translation components.

This package is intended to be used as a glue for the packages that provide the
above mentioned functionality.

This package provides, initially, form rendering with [Twig][3], validation with
[Zend input filter][4], CSRF tokens with [Symfony Security CSRF][5], captcha
elements with [Zend Captcha][6].

All these dependencies are *optional* and more adapters can be added to provide
the same functionality with other packages.

If you are already using a form component as part of its corresponding framework,
you probably will not find this package very useful. This package is intended to
be used when you need a light integration and you don't need or want to install
more packages than you already have.

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

Another concern is validation, you could use this package only to render forms and keep on
validating and sanitizing your form inputs without having to couple validation components
to your form.

For instance, if you are using the Zend input filter package, you could validate your inputs and pass
the error messages directly to the view. Suppose you have this filter to validate your form.

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

And the form would not be aware of the library that performs the validation. However, this package
provides an optional integration in order to encapsulate the behaviour to validate form inputs.

```php
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;

$form = new LoginForm();
$validator = new InputFilterValidator(new LoginFilter());

$form->submit($_POST);

$validator->validate($form); // Form would have the errors messages, if needed, and its values would be filtered
```

### Form rendering

This package uses Twig and is heavily inspired by the way the Symfony form component renders a
form. Form rendering can be customized through *themes*. A theme is a set of Twig templates which
contain *blocks* to customize the way a form element is rendered.

A form element is rendered in three parts

* A label
* The HTML element
* Its error messages, if any.

This package has 2 built-in themes:

* The default layout, which groups form elements in divs
* The Bootstrap 3 layout which extends the default layout

In order to use the themes you have to register the `FormExtension` provided in this package.

```php
use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;

$loader = new Twig_Loader([
    'vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig', // Path to themes
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
Once you have the form extension configured, you can pass your form to any Twig template. Instead
of passing the form you pass its view representation.

```php
$view->render('user/login.html.twig', [
    'form' => $loginForm->buildView(),
]);
```

The extension defines some functions, among the most important are `form_start`, `form_end`, and
`element_row`. The first 2 functions are simple.

```twig
{{ form_start(login) }}
{{ element_row(login.username, {'label': 'Username', 'attr': {'id': 'username'}}) }}
{{ element_row(login.password, {'label': 'Password', 'attr': {'id': 'password'}}) }}
<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-home"></span> Login
</button>
{{ form_end() }}
```

The `element_row` has 2 parameters, the form element, and an associative array of options. The options
are not mandatory, and can be explained as follows:

* `label`. The element 's label
* `label_attr`. The label's HTML attributes
* `attr`. The elements HTML  attributes
* `options`. This one is used mainly to override the default blocks that render the elements
(label, element and error messages, among others).

In the previous example we use the options to define the elements labels, and its HTML ID which could
be used for client side validation, for instance.

If you want to display a form element in a different way only for a template, you could add
the template that renders the form to the theme's templates and define a custom block.
Suppose you have an `ProductForm` class with 3 form elements, a text element with the name of
the product, a text area with an optional description, and another text element to enter a
unit price. To add the current template to the theme you will need to use the `form_theme` token
and pass the value `self` as argument, you can add more than one template this way, therefore
the value should be inside an array.

```twig
{# Use this template as an inline layout #}
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
{{ element_row(form.description, {'label': 'Description', 'attr': {'id': 'description'}}) }}
{# Override the element's default rendering block with the block option #}
{{ element_row(form.unitPrice, {'label': 'Unit price', 'options': {'block': 'money'}}) }}
<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-th-list"></span> Add to catalog
</button>
{{ form_end() }}
```

You can find some working examples (a small Slim application) in this [repository][7]

[1]: http://symfony.com/doc/current/components/form/introduction.html
[2]: http://framework.zend.com/manual/current/en/modules/zend.form.intro.html
[3]: http://twig.sensiolabs.org/
[4]: http://framework.zend.com/manual/current/en/modules/zend.input-filter.intro.html
[5]: https://github.com/symfony/security-csrf
[6]: http://framework.zend.com/manual/current/en/modules/zend.captcha.intro.html
[7]: https://github.com/MontealegreLuis/easy-forms-examples
