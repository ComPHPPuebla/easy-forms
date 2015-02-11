# CAPTCHA elements

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
