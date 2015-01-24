<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use EasyForms\Elements\Checkbox;
use EasyForms\Elements\Radio;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use PHPUnit_Framework_TestCase as TestCase;

class FormRendererBootstrap3LayoutTest extends TestCase
{
    use ProvidesLayoutConfiguration;

    public function setUp()
    {
        $this->configureLayout('layouts/bootstrap3.html.twig');
    }

    /** @test */
    public function it_should_render_a_form_element_error_messages()
    {
        $name = new Text('name');
        $name->setMessages([
            'Name is required and cannot be empty',
            'Name length should be at least 3 characters',
        ]);

        $label = $this->renderer->renderErrors($name->buildView());
        $this->assertEquals(
            '<ul class="list-unstyled"><li class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Name is required and cannot be empty</li><li class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Name length should be at least 3 characters</li></ul>',
            $label
        );

        $name->setMessages([]);
        $label = $this->renderer->renderErrors($name->buildView());
        $this->assertEquals('', $label);
    }

    /** @test */
    public function it_should_render_a_textarea_element()
    {
        $description = new TextArea('description');

        $html = $this->renderer->renderElement($description->buildView(), ['class' => 'js-resize']);

        $this->assertEquals('<textarea name="description" class="js-resize form-control"></textarea>', $html);
    }

    /** @test */
    public function it_should_render_a_select_element()
    {
        $categories = new Select('categories', [100 => 'Electronics', 200 => 'Video games']);
        $categories->setValue(100);

        $html = $this->renderer->renderElement($categories->buildView(), ['class' => 'js-chained']);
        $this->assertEquals(
            '<select name="categories" class="js-chained form-control"><option value="100" selected>Electronics</option><option value="200">Video games</option></select>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_multiple_select_element()
    {
        $categories = new Select('categories', [100 => 'Electronics', 200 => 'Video games']);
        $categories->enableMultipleSelection();
        $categories->setValue([100, 200]);

        $html = $this->renderer->renderElement($categories->buildView(), ['class' => 'js-chained']);
        $this->assertEquals(
            '<select name="categories[]" multiple class="js-chained form-control" multiple><option value="100" selected>Electronics</option><option value="200" selected>Video games</option></select>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_radio_button_element()
    {
        $gender = new Radio('gender', ['M' => 'Male', 'F' => 'Female']);
        $gender->setValue('F');

        $html = $this->renderer->renderElement($gender->buildView(), ['class' => 'js-hidden']);

        $this->assertEquals(
            '&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="gender" class="js-hidden" value="M">Male</label>&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="gender" class="js-hidden" value="F" checked>Female</label>',
            $html
        );
    }

    /** @test */
    public function it_should_render_an_element_row()
    {
        $description = new TextArea('description');
        $description->setValue('123');
        $description->setMessages(['Please enter a valid description']);

        $html = $this->renderer->renderRow($description->buildView(), [
            'label' => 'Description',
            'label_attr' => ['class' => 'form-label'],
            'attr' => ['class' => 'js-resize'],
        ]);

        $this->assertEquals(
            '<div class="form-group has-error"><label class="form-label">Description</label><textarea name="description" class="js-resize form-control">123</textarea><ul class="list-unstyled"><li class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Please enter a valid description</li></ul></div>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_row_with_a_checkbox_element()
    {
        $rememberMe = new Checkbox('remember_me', 'remember');
        $rememberMe->setValue('remember');

        $html = $this->renderer->renderRow($rememberMe->buildView(), [
            'label' => 'Remember me',
            'label_attr' => ['class' => 'form-label'],
            'attr' => ['class' => 'js-validate'],
        ]);

        $this->assertEquals(
            '<div><label class="form-label checkbox-inline"><input type="checkbox" name="remember_me" value="remember" checked class="js-validate">Remember me</label></div>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_row_with_a_radio_button_element()
    {
        $gender = new Radio('gender', ['M' => 'Male', 'F' => 'Female']);
        $gender->setValue('M');

        $html = $this->renderer->renderRow($gender->buildView(), [
            'label' => 'Gender',
            'label_attr' => ['class' => 'form-label'],
            'attr' => ['class' => 'js-validate'],
        ]);

        $this->assertEquals(
            '<div class="form-group"><label class="form-label">Gender</label>&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="gender" class="js-validate" value="M" checked>Male</label>&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="gender" class="js-validate" value="F">Female</label></div>',
            $html
        );
    }
}
