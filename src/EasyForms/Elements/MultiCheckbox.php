<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 *  @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Elements;

use EasyForms\View\ElementView;

class MultiCheckbox extends Choice
{
    /** @var array */
    protected $attributes = [
        'type' => 'checkbox',
    ];

    /**
     * In order to process several values, '[]' are concatenated to the element name
     *
     * @param string $name
     * @param array $choices
     */
    public function __construct($name, array $choices = [])
    {
        parent::__construct("{$name}[]", $choices);
    }

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();

        parent::buildView($view);

        $view->block = 'multi_checkbox';
        $view->rowBlock = 'multi_checkbox_row';

        return $view;
    }
}
