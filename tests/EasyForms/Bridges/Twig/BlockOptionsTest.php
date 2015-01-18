<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use EasyForms\Elements\Hidden;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use PHPUnit_Framework_TestCase as TestCase;

class BlockOptionsTest extends TestCase
{
    /** @var BlockOptions */
    protected $options;

    public function setUp()
    {
        $this->options = new BlockOptions();
    }

    /** @test */
    public function it_should_override_the_block_option_of_an_element()
    {
        $name = new Text('name');
        $nameView = $name->buildView();
        $options = ['block' => 'my_text_element'];

        $this->options->overrideBlocks($nameView, $options);

        $this->assertEquals('my_text_element', $nameView->block);
    }

    /** @test */
    public function it_should_override_the_row_block_option_of_an_element()
    {
        $name = new Text('name');
        $nameView = $name->buildView();
        $options = ['row_block' => 'my_row_block'];

        $this->options->overrideBlocks($nameView, $options);

        $this->assertEquals('my_row_block', $nameView->rowBlock);
    }

    /** @test */
    public function it_should_override_all_the_block_options_of_an_element()
    {
        $name = new Text('name');
        $nameView = $name->buildView();
        $options = ['block' => 'my_text_element', 'row_block' => 'my_row_block'];

        $this->options->overrideBlocks($nameView, $options);

        $this->assertEquals('my_text_element', $nameView->block);
        $this->assertEquals('my_row_block', $nameView->rowBlock);
    }

    /** @test */
    public function it_should_process_an_element_without_a_label()
    {
        $productId = new Hidden('product_id');
        $productIdView = $productId->buildView();
        $options = ['attr' => ['class' => 'js-id']];

        $processedOptions = $this->options->process($productIdView, $options);

        $this->assertEquals([
            'element' => $productIdView,
            'valid' => true,
            'attr' => ['class' => 'js-id', 'type' => 'hidden', 'name' => 'product_id'],
            'options' => [],
        ], $processedOptions);
    }

    /** @test */
    public function it_should_process_an_element_with_a_label()
    {
        $name = new Text('name');
        $nameView = $name->buildView();
        $options = [
            'attr' => ['class' => 'js-tooltip'],
            'label' => 'Name',
            'label_attr' => ['class' => 'required-label'],
        ];

        $processedOptions = $this->options->process($nameView, $options);

        $this->assertEquals([
            'element' => $nameView,
            'valid' => true,
            'attr' => ['class' => 'js-tooltip', 'type' => 'text', 'name' => 'name'],
            'options' => [],
            'label' => 'Name',
            'label_attr' => ['class' => 'required-label'],
        ], $processedOptions);
    }

    /** @test */
    public function it_should_process_an_element_with_options()
    {
        $languages = new Select('languages', [1 => 'PHP', 2 => 'Scala']);
        $languagesView = $languages->buildView();
        $options = [
            'attr' => ['class' => 'js-update'],
            'label' => 'Languages',
            'label_attr' => ['class' => 'required-label'],
            'options' => ['custom_option' => 'custom_value']
        ];

        $processedOptions = $this->options->process($languagesView, $options);

        $this->assertEquals([
            'element' => $languagesView,
            'valid' => true,
            'attr' => ['class' => 'js-update', 'name' => 'languages'],
            'options' => ['custom_option' => 'custom_value'],
            'label' => 'Languages',
            'label_attr' => ['class' => 'required-label'],
        ], $processedOptions);
    }
}
