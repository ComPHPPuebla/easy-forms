<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\Elements;

use EasyForms\Elements\Captcha\CaptchaAdapter;
use PhpSpec\ObjectBehavior;

class CaptchaSpec extends ObjectBehavior
{
    function it_should_build_view_with_the_correct_values_depending_on_the_adapter(CaptchaAdapter $adapter)
    {
        $this->beConstructedWith('captcha', $adapter);
        $adapter->generate()->willReturn($captchaId = '123456');
        $adapter->name()->willReturn('image');
        $adapter->options()->willReturn(['images_url' => $imagesUrl = 'images/capctha']);

        $view = $this->buildView();

        $view->attributes->shouldBe([
            'name' => 'captcha',
        ]);
        $view->value->shouldBe($captchaId);
        $view->options->shouldBe([
            'captcha_id' => $captchaId,
            'images_url' => $imagesUrl,
        ]);
        $view->block->shouldBe('captcha_image');
    }
}
