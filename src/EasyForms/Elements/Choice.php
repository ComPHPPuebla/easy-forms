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

abstract class Choice extends Element
{
    /** @var array */
    protected $choices;

    /**
     * @param string $name
     * @param array $choices
     */
    public function __construct($name, array $choices = [])
    {
        parent::__construct($name);
        $this->setChoices($choices);
    }

    /**
     * @param array $choices
     */
    public function setChoices(array $choices)
    {
        $this->choices = $choices;
    }

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        if (!$view) {
            $view = new ElementView();
        }

        /** @var ElementView $view */
        $view = parent::buildView($view);
        $view->choices = $this->choices;

        return $view;
    }
}
