# Validating forms

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
