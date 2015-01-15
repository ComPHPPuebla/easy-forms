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

class SelectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('cities');
    }

    function it_should_have_access_to_its_options()
    {
        $this->setChoices($cities = [
            '1' => 'Puebla',
            '2' => 'Oaxaca',
            '3' => 'Veracruz',
        ]);

        $view = $this->buildView();

        $view->choices->shouldBeLike($cities);
    }

    function it_should_allow_multiple_selection()
    {
        $this->enableMultipleSelection();

        $view = $this->buildView();

        $view->isMultiple->shouldBe(true);
    }
}
