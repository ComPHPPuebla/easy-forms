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
use EasyForms\Elements\File;
use EasyForms\Elements\Hidden;
use EasyForms\Elements\MultiCheckbox;
use EasyForms\Elements\Radio;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use EasyForms\Form;
use PHPUnit_Framework_TestCase as TestCase;

class FormRendererDefaultLayoutTest extends TestCase
{
    use ProvidesLayoutConfiguration;

    public function setUp()
    {
        $this->configureLayout('layouts/default.html.twig');
    }

    /** @test */
    public function it_should_render_a_form_element_label()
    {
        $name = new Text('name');

        $label = $this->renderer->renderLabel($name->buildView(), 'Name', 'name', ['class' => 'form-label']);

        $this->assertEquals('<label class="form-label" for="name">Name</label>', $label);
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
            '<ul><li>Name is required and cannot be empty</li><li>Name length should be at least 3 characters</li></ul>',
            $label
        );

        $name->setMessages([]);
        $label = $this->renderer->renderErrors($name->buildView());
        $this->assertEquals('', $label);
    }

    /** @test */
    public function it_should_render_the_start_of_a_form()
    {
        $form = new Form();
        $form->add(new File('avatar'));

        $formStart = $this->renderer->renderFormStart($form->buildView(), ['action' => '/profile/update']);
        $this->assertEquals('<form enctype="multipart/form-data" action="/profile/update" method="post">', $formStart);

        $form = new Form();

        $formStart = $this->renderer->renderFormStart($form->buildView(), [
            'action' => '/profile/update',
            'method' => 'get',
        ]);
        $this->assertEquals('<form action="/profile/update" method="get">', $formStart);
    }

    /** @test */
    public function it_should_render_the_end_of_a_form()
    {
        $formEnd = $this->renderer->renderFormEnd();

        $this->assertEquals('</form>', $formEnd);
    }

    /** @test */
    public function it_should_render_a_textarea_element()
    {
        $description = new TextArea('description');

        $html = $this->renderer->renderElement($description->buildView(), ['class' => 'js-resize']);

        $this->assertEquals('<textarea name="description" class="js-resize"></textarea>', $html);
    }

    /** @test */
    public function it_should_render_an_input_element()
    {
        $productId = new Hidden('product_id');

        $html = $this->renderer->renderElement($productId->buildView(), ['class' => 'js-product']);

        $this->assertEquals('<input type="hidden" name="product_id" class="js-product">', $html);
    }

    /** @test */
    public function it_should_render_a_radio_button_element()
    {
        $gender = new Radio('gender', ['M' => 'Male', 'F' => 'Female']);

        $html = $this->renderer->renderElement($gender->buildView(), ['class' => 'js-hidden']);

        $this->assertEquals(
            '<label><input type="radio" name="gender" class="js-hidden" value="M">Male</label><label><input type="radio" name="gender" class="js-hidden" value="F">Female</label>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_checkbox_element()
    {
        $rememberMe = new Checkbox('remember_me');

        $html = $this->renderer->renderElement($rememberMe->buildView(), ['class' => 'js-cookie']);
        $this->assertEquals(
            '<input type="checkbox" name="remember_me" class="js-cookie">',
            $html
        );

        $rememberMe->setValue('remember');
        $html = $this->renderer->renderElement($rememberMe->buildView(), ['class' => 'js-cookie']);
        $this->assertEquals(
            '<input type="checkbox" name="remember_me" value="remember" checked class="js-cookie">',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_multi_checkbox_element()
    {
        $languages = new MultiCheckbox('languages', ['PHP', 'Scala', 'C#']);
        $languages->setValue([0, 1]);

        $html = $this->renderer->renderElement($languages->buildView(), ['class' => 'js-cookie']);
        $this->assertEquals(
            '<label><input type="checkbox" name="languages[]" class="js-cookie" value="0" checked>PHP</label><label><input type="checkbox" name="languages[]" class="js-cookie" value="1" checked>Scala</label><label><input type="checkbox" name="languages[]" class="js-cookie" value="2" >C#</label>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_select_element()
    {
        $categories = new Select('categories', [100 => 'Electronics', 200 => 'Video games']);

        $html = $this->renderer->renderElement($categories->buildView(), ['class' => 'js-chained']);
        $this->assertEquals(
            '<select name="categories" class="js-chained"><option value="100">Electronics</option><option value="200">Video games</option></select>',
            $html
        );

        $categories->setChoices([]);
        $html = $this->renderer->renderElement($categories->buildView(), ['class' => 'js-chained']);
        $this->assertEquals(
            '<select name="categories" class="js-chained"></select>',
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
            '<select name="categories[]" multiple class="js-chained" multiple><option value="100" selected>Electronics</option><option value="200" selected>Video games</option></select>',
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
            '<div><label class="form-label">Description</label><textarea name="description" class="js-resize">123</textarea><ul><li>Please enter a valid description</li></ul></div>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_row_with_a_hidden_element()
    {
        $description = new Hidden('product_id');
        $description->setValue('123');
        $description->setMessages(['This is not a valid product ID']);

        $html = $this->renderer->renderRow($description->buildView(), [
            'attr' => ['class' => 'js-data'],
        ]);

        $this->assertEquals(
            '<input type="hidden" name="product_id" value="123" class="js-data"><ul><li>This is not a valid product ID</li></ul>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_row_with_a_checkbox_element()
    {
        $rememberMe = new Checkbox('remember_me');
        $rememberMe->setValue('remember');
        $rememberMe->setMessages(['This is not a valid value']);

        $html = $this->renderer->renderRow($rememberMe->buildView(), [
            'label' => 'Remember me',
            'label_attr' => ['class' => 'form-label'],
            'attr' => ['class' => 'js-validate'],
        ]);

        $this->assertEquals(
            '<div><label class="form-label"><input type="checkbox" name="remember_me" value="remember" checked class="js-validate">Remember me</label><ul><li>This is not a valid value</li></ul></div>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_row_with_a_multi_checkbox_element()
    {
        $languages = new MultiCheckbox('languages', ['PHP', 'Scala', 'C#']);
        $languages->setValue([0, 1]);
        $languages->setMessages(['Something went wrong']);

        $html = $this->renderer->renderRow($languages->buildView(), [
            'label' => 'Programming languages',
            'label_attr' => ['class' => 'form-label'],
            ['class' => 'js-cookie']
        ]);
        $this->assertEquals(
            '<div><label class="form-label">Programming languages</label><label class="form-label"><input type="checkbox" name="languages[]" value="0" checked>PHP</label><label class="form-label"><input type="checkbox" name="languages[]" value="1" checked>Scala</label><label class="form-label"><input type="checkbox" name="languages[]" value="2" >C#</label><ul><li>Something went wrong</li></ul></div>',
            $html
        );
    }

    /** @test */
    public function it_should_render_a_row_with_a_radio_button_element()
    {
        $gender = new Radio('gender', ['M' => 'Male', 'F' => 'Female']);
        $gender->setValue('M');
        $gender->setMessages(['This is not a valid value']);

        $html = $this->renderer->renderRow($gender->buildView(), [
            'label' => 'Gender',
            'label_attr' => ['class' => 'form-label'],
            'attr' => ['class' => 'js-validate'],
        ]);

        $this->assertEquals(
            '<div><label class="form-label">Gender</label><label><input type="radio" name="gender" class="js-validate" value="M" checked>Male</label><label><input type="radio" name="gender" class="js-validate" value="F">Female</label><ul><li>This is not a valid value</li></ul></div>',
            $html
        );
    }
}
