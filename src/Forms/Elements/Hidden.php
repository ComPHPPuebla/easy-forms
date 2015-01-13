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

class Hidden extends Input
{
    /** @var array */
    protected $attributes = [
        'type' => 'hidden',
    ];

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();
        $view = parent::buildView($view);
        $view->block = 'input';
        $view->rowBlock = 'hidden_row';

        return $view;
    }
}
