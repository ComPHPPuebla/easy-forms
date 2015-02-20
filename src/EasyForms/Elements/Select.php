<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Elements;

use EasyForms\View\ElementView;
use EasyForms\View\SelectView;

class Select extends Choice
{
    /**
     * @param ElementView $view
     * @return SelectView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new SelectView();

        /** @var SelectView $view */
        $view = parent::buildView($view);

        $view->block = 'select';
        $view->isMultiple = $this->isMultiple();

        return $view;
    }

    /**
     * This select allows multiple selection
     *
     * @return Element
     */
    public function enableMultipleSelection()
    {
        $this->attributes['multiple'] = true;

        return $this;
    }

    /**
     * @return boolean
     */
    protected function isMultiple()
    {
        return isset($this->attributes['multiple']);
    }
}
