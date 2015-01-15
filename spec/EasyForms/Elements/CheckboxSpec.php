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

class CheckboxSpec extends ObjectBehavior
{
    function it_should_have_checkbox_as_its_type_attribute()
    {
        $this->beConstructedWith('remember_me', 'remember');

        $view = $this->buildView();

        $view->attributes['type']->shouldBe('checkbox');
    }

    function it_should_build_view_with_the_correct_checked_value()
    {
        $this->beConstructedWith('remember_me', 'remember');

        $this->setValue('remember');
        $view = $this->buildView();

        $view->isChecked->shouldBe(true);
    }
}
