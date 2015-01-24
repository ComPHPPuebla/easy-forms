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
     * Initially only the HTML attribute 'value' is set, since we do not have information of the value provided by the
     * user at this moment
     *
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name);
        $this->attributes['value'] = $value;
    }


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
     * @return ElementView
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
