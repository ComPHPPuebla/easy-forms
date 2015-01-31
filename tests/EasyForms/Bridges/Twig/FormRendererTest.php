<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use EasyForms\Elements\Captcha;
use EasyForms\Elements\Text;
use PHPUnit_Framework_TestCase as TestCase;

class FormRendererTest extends TestCase
{
    use ProvidesLayoutConfiguration;

    public function setUp()
    {
        $this->configureLayout('layouts/bootstrap3.html.twig');
        $this->environment->getLoader()->addPath(__DIR__ . '/templates');
    }

    /** @test */
    public function it_should_use_a_block_defined_in_the_current_template()
    {
        $price = new Text('price');

        $html = $this->environment->render('forms/payment.html.twig', [
            'price' => $price->buildView(),
        ]);

        $this->assertEquals(
            '<div class="form-group"><label for="price">Price</label><div class="input-group"><div class="input-group-addon">$</div><input type="text" name="price" id="price" class="form-control"><div class="input-group-addon">.00</div></div></div>',
            $html
        );
    }

    /** @test */
    public function it_should_use_all_the_blocks_defined_in_the_current_template()
    {
        $price = new Text('price');
        $captcha = new Captcha('captcha', new FakeCaptchaAdapter());

        $html = $this->environment->render('forms/captcha.html.twig', [
            'price' => $price->buildView(),
            'captcha' => $captchaView = $captcha->buildView(),
        ]);

        $this->assertEquals(
            sprintf(
                '<div class="form-group"><label for="price">Price</label><div class="input-group"><div class="input-group-addon">$</div><input type="text" name="price" id="price" class="form-control"><div class="input-group-addon">.00</div></div></div><div class="form-group"><label>Please type this word below</label><img alt="" src="/images/capctha/%s.png"><br><input name="captcha[input]" type="text"><input name="captcha[id]" type="hidden" value="%s"></div>',
                $captchaView->options['captcha_id'],
                $captchaView->options['captcha_id']
            ),
            $html
        );
    }
}

class FakeCaptchaAdapter implements Captcha\CaptchaAdapter
{
    public function generate() { return mt_rand(); }

    public function word() { return md5(mt_rand()); }

    public function name() { return 'image'; }

    public function options() { return ['image_url' => '/images/capctha']; }
}
