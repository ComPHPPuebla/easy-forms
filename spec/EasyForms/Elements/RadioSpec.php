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

class RadioSpec extends ObjectBehavior
{
    function it_should_use_a_specific_row_block_to_render()
    {
        $this->beConstructedWith('gender', ['M' => 'Male', 'F' => 'Female']);

        $view = $this->buildView();

        $view->rowBlock->shouldBe('radio_row');  // The form element is between the label generally.
    }
}
