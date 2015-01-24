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

class MultiCheckboxSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('languages');
    }

    function it_should_have_access_to_its_options()
    {
        $this->setChoices($languages = [
            1 => 'PHP',
            2 => 'Scala',
            3 => 'C#',
        ]);

        $view = $this->buildView();

        $view->choices->shouldBeLike($languages);
    }
}
