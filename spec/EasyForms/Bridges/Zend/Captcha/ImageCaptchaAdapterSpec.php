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
use Zend\Captcha\Image;

class ImageCaptchaAdapterSpec extends ObjectBehavior
{
    function let(Image $captcha)
    {
        $this->beConstructedWith($captcha);
    }

    function it_should_generate_captcha_id(Image $captcha)
    {
        $this->generate();

        $captcha->generate()->shouldHaveBeenCalled();
    }

    function it_should_get_the_configured_captcha_image_url(Image $captcha)
    {
        $this->options();

        $captcha->getImgUrl()->shouldHaveBeenCalled();
    }

    function it_should_get_the_captcha_adapter_name()
    {
        $this->name()->shouldBe('image');
    }
}
