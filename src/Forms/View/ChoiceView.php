<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\View;

class ChoiceView extends ElementView
{
    /**
     * @param string $value
     * @return bool
     */
    public function isSelected($value)
    {
        return $this->value === $value;
    }
}
