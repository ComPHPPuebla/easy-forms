# CSRF tokens

In order to protect your application from Cross Site Request Forgery (CSRF) attacks
you can generate and validate secret tokens and include them in your forms. This
package integrates the
[Symfony Security CSRF package](https://packagist.org/packages/symfony/security-csrf)
to do that task.

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
use EasyForms\Bridges\Symfony\Security\CsrfTokenProvider;
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
