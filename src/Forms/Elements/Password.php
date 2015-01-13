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

class Password extends Input
{
    /** @var array */
    protected $attributes = [
        'type' => 'password',
    ];

    /**
     * Clear value before rendering
     *
     * @param ElementView $view = null
     * @return \Forms\View\ElementView
     */
    public function buildView(ElementView $view = null)
    {
        $this->setValue('');

        return parent::buildView();
    }
}
