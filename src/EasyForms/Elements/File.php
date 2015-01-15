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

class File extends Input
{
    /** @var array */
    protected $attributes = [
        'type' => 'file',
    ];

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();
        $view = parent::buildView($view);

        $this->setValue(null);

        // Clearing the File value modifies the attributes and value properties
        $view->attributes = $this->attributes;
        $view->value = $this->value;

        return $view;
    }
}
