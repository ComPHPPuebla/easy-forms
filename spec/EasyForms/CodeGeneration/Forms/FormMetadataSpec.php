<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\CodeGeneration\Forms;

use PhpSpec\ObjectBehavior;

class FormMetadataSpec extends ObjectBehavior
{
    function it_should_not_add_an_element_with_an_invalid_type()
    {
        $this->shouldThrow()->during('populate', ['My\Awesome\LoginForm', ['username' => ['type' => 'foo-type']]]);
    }

    function it_should_add_valid_elements()
    {
        $this->populate('My\Awesome\LoginForm', [
            'username' => ['type' => 'text', 'optional' => false, 'multipleSelection' => false, 'value' => null],
            'password' => ['type' => 'password', 'optional' => false, 'multipleSelection' => false, 'value' => null],
            'remember_me' => ['type' => 'checkbox', 'optional' => false, 'multipleSelection' => false, 'value' => null],
        ]);

        $this->elements()->shouldHaveCount(3);
    }

    function it_should_set_correct_paths_for_this_form()
    {
        $this->populate('My\Awesome\LoginForm', []);
        $this->setTargetDirectory('src/');

        $this->classDirectory()->shouldBe('src/My/Awesome');
        $this->classFilename()->shouldBe('src/My/Awesome/LoginForm.php');
    }

    function it_should_set_class_names_correctly()
    {
        $this->populate('My\Awesome\LoginForm', []);

        $this->className()->shouldBe('LoginForm');
        $this->formNamespace()->shouldBe('My\Awesome');
    }
}
