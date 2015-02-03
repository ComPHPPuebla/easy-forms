# Easy form handling for PHP

[![Build Status](https://travis-ci.org/ComPHPPuebla/easy-forms.svg?branch=master)](https://travis-ci.org/ComPHPPuebla/easy-forms)

Most popular PHP packages (like [Symfony][1] and [Zend][2]) provide support for the following tasks
related to forms processing.

* Rendering
* Validation
* Translation

However both packages require several dependencies, even for the simplest case (internationalization,
validation, and event dispatching packages for instance). For form rendering both packages need to
use translation components.

This package is intended to be used as a glue for the packages that provide the above mentioned
functionality.

Initially this package provides form rendering with [Twig][3], validation with [Zend input filter][4],
CSRF tokens with [Symfony Security CSRF][5], captchas with [Zend Captcha][6].

All these dependencies are *optional* and more adapters can be added to provide the same
functionality with another packages.

If you are already using a form component as part of its corresponding framework, you probably will
not find this package very useful. This package is intended to be used when you need a light
integration and you don't need or want to install more packages than you already have or need.

## Creating forms

One of the goals of this package is separating the functionality to process the form in the backend
from the rendering which is a frontend concern.

When you create a form you only need to know the elements names, and its type, in order to know
how to retrieve its values and whether an element can have multiple values or not. Other HTML
attributes like classes, IDS or the label for an element should not be defined in the element itself, all
those values should be specified when the form is rendered, because that is a presentation concern.

The simplest way to create a form is inheriting from `EasyForms\Form` and add elements to it in the
constructor.

```php
class LoginForm extends EasyForms\Form
{
    public function __construct()
    {
        $this
            ->add(new EasyForms\Elements\Text('username')
            ->add(new EasyForms\Elements\Password('password')
        ;
    }
}
```

## Validating forms

Another concern is validation, you could use this package only to render forms and keep on
validating and sanitizing your form inputs without having to couple it to your form.

For instance, if you are using the Zend's input filter package, you could validate your inputs and pass
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

You could validate your form this way:

```php
$form = new LoginForm();
$filter = new LoginFilter();

$filter->setData($_POST);
if (!$filter->isValid() {
    $form->setErrorMessages($filter->getMessages());
}
```

And the form would not be aware of the library that performs the validation. However this package
provides an optional integration in order to avoid repeating the above code.

```php
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;

$form = new LoginForm();
$validator = new InputFilterValidator(new LoginFilter());

$form->submit($_POST);

$isValid = $validator->validate($form);

// Form would have the errors messages, if needed, and its values would be filtered
```

## Form rendering

This package uses Twig and is heavily inspired by the way the Symfony form component renders a
form. Form rendering can be customized through themes. A theme is a set of Twig templates which
contain blocks that customize the way a form element is rendered.

A form element is rendered in three parts

* A label
* The HTML element
* Its error messages, if any

This package has 2 built-in themes:

* The default layout, which groups a form element in divs
* The Bootstrap 3 layout which extends the default layout

In order to use them you have to register the `FormExtension` provided in this package.

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
// use the bootstrap layout
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

The extension defines some functions, among the most importants are `form_start`, `form_end`, and
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

The `form_row` has 2 parameters, the form element, and an associative array of options. The options
are not mandatory, and can be explained as follows:

* `label`. The element 's label
* `label_attr`. The label's HTML attributes
* `attr`. The elements HTML  attributes
* `options`. This one is used mainly to override the default blocks that render the elements parts
(label, element and error messages, among others).

In the previous example we use the options to define the elements labels, and its HTML ID which could
be used for client side validation, for instance.


[1]: http://symfony.com/doc/current/components/form/introduction.html
[2]: http://framework.zend.com/manual/current/en/modules/zend.form.intro.html
[3]: http://twig.sensiolabs.org/
[4]: http://framework.zend.com/manual/current/en/modules/zend.input-filter.intro.html
[5]: https://github.com/symfony/security-csrf
[6]: http://framework.zend.com/manual/current/en/modules/zend.captcha.intro.html
