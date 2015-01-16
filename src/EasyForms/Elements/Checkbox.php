<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Elements;

use Assert\Assertion;
use EasyForms\View\CheckboxView;
use EasyForms\View\ElementView;

class Checkbox extends Input
{
    /** @var array */
    protected $attributes = [
        'type' => 'checkbox',
    ];

    /**
     * If a value is set, the 'checked' attribute should be added.
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        $this->attributes['checked'] = true;
    }


    /**
     * @param ElementView $view
     * @return CheckboxView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();

        /** @var ElementView $view */
        $view = parent::buildView($view);

        $view->rowBlock = 'checkbox_row';

        return $view;
    }
}
