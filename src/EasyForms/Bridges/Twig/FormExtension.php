<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use EasyForms\Bridges\Twig\TokenParser\AddThemeTokenParser;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

class FormExtension extends Extension
{
    /** @var FormRenderer */
    protected $renderer;

    /**
     * @param FormRenderer $renderer
     */
    public function __construct(FormRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return FormRenderer
     */
    public function renderer()
    {
        return $this->renderer;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('form_start', [$this->renderer, 'renderFormStart'], ['is_safe' => ['html']]),
            new SimpleFunction('element_row', [$this->renderer, 'renderRow'], ['is_safe' => ['html']]),
            new SimpleFunction('label', [$this->renderer, 'renderLabel'], ['is_safe' => ['html']]),
            new SimpleFunction('element', [$this->renderer, 'renderElement'], ['is_safe' => ['html']]),
            new SimpleFunction('errors', [$this->renderer, 'renderErrors'], ['is_safe' => ['html']]),
            new SimpleFunction('form_end', [$this->renderer, 'renderFormEnd'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new AddThemeTokenParser(),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'easy_forms';
    }
}
