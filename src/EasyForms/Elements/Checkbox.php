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
    /** @var string */
    protected $checkedValue;

    /** @var array */
    protected $attributes = [
        'type' => 'checkbox',
    ];

    /**
     * @param string $name
     * @param string $checkedValue
     * @param array $attributes
     */
    public function __construct($name, $checkedValue, array $attributes = [])
    {
        parent::__construct($name, $attributes);
        $this->setCheckedValue($checkedValue);
    }


    /**
     * @param string $value
     */
    protected function setCheckedValue($value)
    {
        Assertion::notEmpty($value);

        $this->checkedValue = $value;
    }

    /**
     * @param ElementView $view
     * @return CheckboxView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new CheckboxView();

        /** @var CheckboxView $view */
        $view = parent::buildView($view);

        $view->rowBlock = 'checkbox_row';
        $view->isChecked = $this->value === $this->checkedValue;

        return $view;
    }
}
