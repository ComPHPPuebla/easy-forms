<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\Elements;

use PhpSpec\ObjectBehavior;

class PasswordSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('password');
    }

    function it_should_have_password_as_its_type_attribute()
    {
        $view = $this->buildView();

        $view->attributes['type']->shouldBe('password');
    }

    function it_should_clear_its_value_when_building_its_view()
    {
        $view = $this->buildView();

        $view->value->shouldBe('');
        $view->attributes['value']->shouldBe('');
    }
}
