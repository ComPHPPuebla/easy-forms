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

class BlockOptions
{
    /**
     * @param ElementView $element
     * @param array $options
     * @return array
     */
    public function process(ElementView $element, array $options)
    {
        $attr = $this->mergeAttributes($element, $options);
        isset($options['options']) && $this->overrideBlocks($element, $options['options']);
        $opt = $this->mergeOptions($element, $options);

        return array_merge([
            'element' => $element,
            'isValid' => $element->isValid,
            'attr' => $attr,
            'options' => $opt,
        ], $options);
    }

    /**
     * @param ElementView $element
     * @param array $options
     */
    public function overrideBlocks(ElementView $element, array $options)
    {
        isset($options['block']) && $element->block = $options['block'];
        isset($options['row_block']) && $element->rowBlock = $options['row_block'];
    }

    /**
     * @param ElementView $element
     * @param array $options
     * @return array
     */
    protected function mergeAttributes(ElementView $element, array &$options)
    {
        $attr = isset($options['attr']) ? array_merge($element->attributes, $options['attr']) : $element->attributes;
        unset($options['attr']);

        return $attr;
    }

    /**
     * @param ElementView $element
     * @param array $options
     * @return array
     */
    protected function mergeOptions(ElementView $element, array &$options)
    {
        $opt = isset($options['options']) ? array_merge($element->options, $options['options']) : $element->options;
        unset($options['options']);

        return $opt;
    }
}
