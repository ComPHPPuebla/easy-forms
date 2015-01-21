<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use EasyForms\View\ElementView;
use EasyForms\View\FormView;
use Twig_Template as Template;

class FormRenderer
{
    /** @var Theme */
    protected $theme;

    /** @var BlockOptions */
    protected $options;

    /**
     * @param FormTheme $theme
     * @param BlockOptions $options
     */
    public function __construct(FormTheme $theme, BlockOptions $options)
    {
        $this->theme = $theme;
        $this->options = $options;
    }

    /**
     * @param Template[] $templates
     */
    public function addTemplates(array $templates)
    {
        foreach ($templates as $template) {
            !$template instanceof Template && $template = $this->theme->loadTemplate($template);
            $this->theme->addTemplate($template);
        }
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
        return $this->renderBlock($element->rowBlock, $this->options->process($element, $options));
    }

    /**
     * @param ElementView $element
     * @param array $attributes
     * @param array $options
     * @return string
     */
    public function renderElement(ElementView $element, $attributes = [], array $options = [])
    {
        $this->options->overrideBlocks($element, $options);

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
        $template = $this->theme->loadTemplateFor($name);

        ob_start();

        $template->displayBlock($name, $vars, $this->theme->blocks());

        return ob_get_clean();
    }
}
