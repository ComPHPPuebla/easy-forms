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
use EasyForms\View\ChoiceView;

class Radio extends Choice
{
    /** @var array */
    protected $attributes = [
        'type' => 'radio',
    ];

    /**
     * @param ElementView $view
     * @return ChoiceView
     */
    public function buildView(ElementView $view = null)
    {
        if (!$view) {
            $view = new ChoiceView();
        }

        /** @var ChoiceView $view */
        $view = parent::buildView($view);
        $view->block = 'radio';
        $view->rowBlock = 'radio_row';

        return $view;
    }
}
