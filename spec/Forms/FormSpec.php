<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\Forms;

use Forms\Elements\File;
use Forms\Elements\Element;
use Forms\Elements\Password;
use Forms\Elements\Text;
use Forms\View\ElementView;
use PhpSpec\ObjectBehavior;

class FormSpec extends ObjectBehavior
{
    function it_should_add_elements(Element $element)
    {
        $this->add($element);

        $element->name()->shouldHaveBeenCalled();
    }

    function it_should_have_access_to_the_submitted_data()
    {
        $username = new Text('username');
        $password = new Password('password');

        $this
            ->add($username)
            ->add($password);

        $this->submit([
            'username' => 'john.doe',
            'password' => 'changeme',
        ]);

        $this->values()->shouldBe([
            'username' => 'john.doe',
            'password' => 'changeme',
        ]);
    }

    function it_should_have_null_values_if_form_is_not_submitted()
    {
        $username = new Text('username');
        $password = new Password('password');

        $this
            ->add($username)
            ->add($password);

        $this->values()->shouldBe([
            'username' => null,
            'password' => null,
        ]);
    }

    function it_should_add_error_messages_to_the_corresponding_element(Text $username, Password $password)
    {
        $usernameMessages = ['Username cannot be empty'];
        $username->beConstructedWith(['username']);
        $username->name()->willReturn('username');
        $password->beConstructedWith(['password']);
        $password->name()->willReturn('password');

        $username->setMessages($usernameMessages)->shouldBeCalled();

        $this->add($username)->add($password);

        $this->setMessages([
            'username' => $usernameMessages,
        ]);
    }

    function it_should_create_view()
    {
        $username = new Text('username');
        $password = new Password('password');

        $this
            ->add($username)
            ->add($password);

        $view = $this->buildView();

        $view->offsetGet('username')->shouldBeAnInstanceOf(ElementView::class);
        $view->offsetGet('password')->shouldBeAnInstanceOf(ElementView::class);
    }

    function it_should_set_correct_content_type_when_file_element_is_added()
    {
        $this->add(new File('avatar'));

        $view = $this->buildView();

        $view->attributes['enctype']->shouldBe('multipart/form-data');
    }
}
