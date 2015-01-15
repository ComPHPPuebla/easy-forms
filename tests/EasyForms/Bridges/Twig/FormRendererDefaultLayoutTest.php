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
use EasyForms\Elements\Radio;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use EasyForms\Form;
use PHPUnit_Framework_TestCase as TestCase;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

class FormRendererDefaultLayoutTest extends TestCase
{
    /** @var FormRenderer */
    protected $renderer;

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
        $rememberMe = new Checkbox('remember_me', 'remember');

        $html = $this->renderer->renderElement($rememberMe->buildView(), ['class' => 'js-cookie']);
        $this->assertEquals(
            '<input type="checkbox" name="remember_me" class="js-cookie">',
            $html
        );

        $rememberMe->setValue('remember');

        $html = $this->renderer->renderElement($rememberMe->buildView(), ['class' => 'js-cookie']);
        $this->assertEquals(
            '<input type="checkbox" name="remember_me" value="remember" class="js-cookie">', // This should be checked
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

    /**
     * @param string $path Relative path to layout file
     */
    protected function configureLayout($path)
    {
        $loader = new Loader([
            str_replace('tests', 'src', __DIR__)
        ]);
        $environment = new Environment($loader, [
            'debug' => true,
            'strict_variables' => true,
        ]);

        $this->renderer = new FormRenderer($environment, $path);

        $environment->addExtension(new FormExtension($this->renderer));
    }
}
