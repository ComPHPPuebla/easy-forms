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

class TextSpec extends ObjectBehavior
{
    function it_should_have_text_as_its_type_attribute()
    {
        $this->beConstructedWith('username');

        $view = $this->buildView();

        $view->attributes['type']->shouldBe('text');
    }

    function it_should_have_access_to_its_error_messages()
    {
        $messages = [
            'This value should not be empty.',
            'This value\'s length should be 10.',
        ];
        $this->beConstructedWith('username');

        $this->setMessages($messages);

        $view = $this->buildView();

        $view->messages->shouldBeLike($messages);
    }

    function it_should_have_access_to_the_element_value()
    {
        $username = 'john.doe';
        $this->beConstructedWith('username');

        $this->setValue($username);

        $this->value()->shouldBeLike($username);

        $view = $this->buildView();

        $view->attributes['value']->shouldBeLike($username);
    }
}
