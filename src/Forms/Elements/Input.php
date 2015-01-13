<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\Elements;

use Forms\View\ElementView;

abstract class Input extends Element
{
    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        $this->attributes['value'] = $value;
    }

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();
        $view = parent::buildView($view);
        $view->block = 'input';

        return $view;
    }
}
