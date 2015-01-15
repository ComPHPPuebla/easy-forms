<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\View;

use PhpSpec\ObjectBehavior;

class ChoiceViewSpec extends ObjectBehavior
{
    function it_should_identify_the_selected_value_correctly()
    {
        $this->value = 'PHP';

        $this->isSelected('Java')->shouldBe(false);
        $this->isSelected('PHP')->shouldBe(true);
    }
}
