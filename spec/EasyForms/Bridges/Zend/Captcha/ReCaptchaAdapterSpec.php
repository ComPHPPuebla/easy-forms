<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\Bridges\Zend\Captcha;

use PhpSpec\ObjectBehavior;
use Zend\Captcha\ReCaptcha;

class ReCaptchaAdapterSpec extends ObjectBehavior
{
    function let(ReCaptcha $captcha)
    {
        $this->beConstructedWith($captcha);
    }

    function it_should_generate_captcha_id(ReCaptcha $captcha)
    {
        $this->generate();

        $captcha->generate()->shouldHaveBeenCalled();
    }

    function it_should_get_the_configured_public_key(ReCaptcha $captcha)
    {
        $this->options();

        $captcha->getPubkey()->shouldHaveBeenCalled();
    }

    function it_should_get_the_captcha_adapter_name()
    {
        $this->name()->shouldBe('re_captcha');
    }
}
