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

class HiddenSpec extends ObjectBehavior
{
    function it_should_use_a_specific_row_block_to_render()
    {
        $this->beConstructedWith('product_id');

        $view = $this->buildView();

        $view->rowBlock->shouldBe('hidden_row');  // Label is skipped when rendering a hidden element
    }
}
