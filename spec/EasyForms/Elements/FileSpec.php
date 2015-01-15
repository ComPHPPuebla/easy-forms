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

class FileSpec extends ObjectBehavior
{
    function it_should_build_its_view_element_with_an_empty_value()
    {
        $this->beConstructedWith('avatar');

        $view = $this->buildView();

        $view->value->shouldBe(null);
        $view->attributes['value']->shouldBe(null);
    }
}
