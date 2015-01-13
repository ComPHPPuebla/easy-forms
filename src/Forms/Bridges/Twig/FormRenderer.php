<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\Bridges\Twig;

use Forms\View\ElementView;
use Forms\View\FormView;
use Twig_Environment as Environment;
use Twig_Template as Template;

class FormRenderer
{
    /** @var Environment */
    protected $environment;

    /** @var string */
    protected $themePath;

    /** @var \Twig_Template  */
    protected $theme;

    /**
     * @param Environment $environment
     * @param string $themePath
     */
    public function __construct(Environment $environment, $themePath)
    {
        $this->environment = $environment;
        $this->themePath = $themePath;
    }

    /**
     * @return \Twig_TemplateInterface
     */
    protected function theme()
    {
        if (!$this->theme instanceof Template) {
            $this->theme = $this->environment->loadTemplate($this->themePath);
        }

        return $this->theme;
    }

    /**
     * @param ElementView $element
     * @param array $options {
     *     @var array   $attr       This element HTML attributes
     *     @var string  $label      The display name for this element
     *     @var array   $label_attr The HTML attributes for this element's label
     * }
     * @return string
     */
    public function renderRow(ElementView $element, array $options = [])
    {
        $attr = isset($options['attr']) ? array_merge($element->attributes, $options['attr']) : $element->attributes;
        unset($options['attr']);

        $opt = isset($options['options']) ? array_merge($element->options, $options['options']) : $element->options;
        unset($options['options']);

        return $this->renderBlock($element->rowBlock, array_merge([
            'element' => $element,
            'valid' => $element->valid,
            'attr' => $attr,
            'options' => $opt,
        ], $options));
    }

    /**
     * @param ElementView $element
     * @param array $attributes
     * @param array $options
     * @return string
     */
    public function renderElement(ElementView $element, $attributes = [], array $options = [])
    {
        return $this->renderBlock($element->block, [
            'element' => $element,
            'attr' => array_merge($element->attributes, $attributes),
            'value' => $element->value,
            'options' => array_merge($element->options, $options),
            'choices' => $element->choices,
        ]);
    }

    /**
     * @param FormView $form
     * @param array $attributes
     * @return string
     */
    public function renderFormStart(FormView $form, array $attributes = [])
    {
        return $this->renderBlock('form_start', [
            'attr' => array_merge($form->attributes, $attributes),
        ]);
    }

    /**
     * @return string
     */
    public function renderFormEnd()
    {
        return $this->renderBlock('form_end');
    }

    /**
     * @param ElementView $element
     * @param string $label
     * @param string $id
     * @param array $attributes
     * @return string
     */
    public function renderLabel(ElementView $element, $label, $id, array $attributes = [])
    {
        $id && $attributes['for'] = $id;

        return $this->renderBlock('label', [
            'label' => $label,
            'attr' => $attributes,
            'is_required' => $element->isRequired,
        ]);
    }

    /**
     * @param ElementView $element
     * @return string
     */
    public function renderErrors(ElementView $element)
    {
        return $this->renderBlock('errors', [
            'errors' => $element->messages,
        ]);
    }

    /**
     * @param string $name
     * @param array $vars
     * @return string
     */
    protected function renderBlock($name, array $vars = [])
    {
        ob_start();

        $this->theme()->displayBlock($name, $vars);

        return ob_get_clean();
    }
}
