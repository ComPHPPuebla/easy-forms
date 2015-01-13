<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\Forms\Elements;

use PhpSpec\ObjectBehavior;

class TextAreaSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('description');
    }

    function it_should_have_access_to_its_name_attribute()
    {
        $view = $this->buildView();

        $view->attributes->shouldBeArray();
        $view->attributes->shouldHaveCount(1);
        $view->attributes['name']->shouldBe('description');
    }

    function it_should_use_a_specific_block_for_rendering()
    {
        $view = $this->buildView();

        $view->block->shouldBe('textarea');
    }
}
