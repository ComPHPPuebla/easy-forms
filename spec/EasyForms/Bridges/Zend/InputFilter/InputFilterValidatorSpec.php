<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\Bridges\Zend\InputFilter;

use EasyForms\Form;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\InputFilter\InputFilter;

class InputFilterValidatorSpec extends ObjectBehavior
{
    function it_should_not_add_messages_to_form_when_validation_succeeds(Form $form, InputFilter $filter)
    {
        $submittedValues = [
            'username' => 'john.doe',
            'password' => 'changeme',
        ];

        $this->beConstructedWith($filter);
        $filter->setData($submittedValues)->shouldBeCalled();
        $filter->isValid()->willReturn(true);
        $filter->getValues()->willReturn($submittedValues);
        $form->values()->willReturn($submittedValues);
        $form->submit($submittedValues)->shouldBeCalled();

        $form->setErrorMessages(Argument::type('array'))->shouldNotBeCalled();

        $this->validate($form);
    }

    function it_should_add_messages_to_form_when_validation_fails(Form $form, InputFilter $filter)
    {
        $submittedValues = [
            'username' => 'john.doe',
            'password' => '',
        ];
        $errors = ['password' => 'Password cannot be empty'];

        $this->beConstructedWith($filter);
        $filter->setData($submittedValues)->shouldBeCalled();
        $filter->isValid()->willReturn(false);
        $filter->getMessages()->willReturn($errors);
        $form->values()->willReturn($submittedValues);

        $form->setErrorMessages($errors)->shouldBeCalled();

        $this->validate($form);
    }
}
