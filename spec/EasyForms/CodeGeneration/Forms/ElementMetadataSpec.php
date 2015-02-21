<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\CodeGeneration\Forms;

use EasyForms\Elements\Checkbox;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use PhpSpec\ObjectBehavior;

class ElementMetadataSpec extends ObjectBehavior
{
    function it_should_configure_a_required_element()
    {
        $this->beConstructedWith(Text::class);
        $this->setElementName('last_name');
        $this->configure(['optional' => false, 'multipleSelection' => false, 'value' => null]);

        $this->__toString()->shouldBe('new Text(\'last_name\')');
    }

    function it_should_configure_an_optional_element()
    {
        $this->beConstructedWith(Text::class);
        $this->setElementName('last_name');
        $this->configure(['optional' => true, 'multipleSelection' => false, 'value' => null]);

        $this->__toString()->shouldBe('(new Text(\'last_name\'))->makeOptional()');
    }

    function it_should_configure_a_checkbox_default_value()
    {
        $this->beConstructedWith(Checkbox::class);
        $this->setElementName('remember_me');
        $this->configure(['optional' => true, 'multipleSelection' => false, 'value' => 'remember']);

        $this->__toString()->shouldBe('(new Checkbox(\'remember_me\', \'remember\'))->makeOptional()');
    }

    function it_should_configure_an_element_with_choices()
    {
        $this->beConstructedWith(Select::class);
        $this->setElementName('languages');
        $this->configure([
            'optional' => false,
            'multipleSelection' => false,
            'value' => null,
            'choices' => ['php' => 'PHP', 'scala' => 'Scala'],
        ]);

        $this->__toString()->shouldBe('new Select(\'languages\', [\'php\' => \'PHP\', \'scala\' => \'Scala\'])');
    }

    function it_should_configure_a_select_with_multiple_selection()
    {
        $this->beConstructedWith(Select::class);
        $this->setElementName('languages');
        $this->configure([
            'optional' => false,
            'multipleSelection' => true,
            'value' => null,
            'choices' => ['php' => 'PHP', 'scala' => 'Scala'],
        ]);

        $this->__toString()->shouldBe(
            '(new Select(\'languages\', [\'php\' => \'PHP\', \'scala\' => \'Scala\']))->enableMultipleSelection()'
        );
    }

    function it_should_configure_an_optional_select_with_multiple_selection()
    {
        $this->beConstructedWith(Select::class);
        $this->setElementName('languages');
        $this->configure([
            'optional' => true,
            'multipleSelection' => true,
            'value' => null,
            'choices' => ['php' => 'PHP', 'scala' => 'Scala'],
        ]);

        $this->__toString()->shouldBe(
            '(new Select(\'languages\', [\'php\' => \'PHP\', \'scala\' => \'Scala\']))->makeOptional()->enableMultipleSelection()'
        );
    }
}
