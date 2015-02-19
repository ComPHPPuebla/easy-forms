<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\CodeGeneration\Forms;

use ReflectionClass;

class ElementMetadata extends ReflectionClass
{
    /** @var array */
    protected $choices = [];

    /**
     * @param array $choices
     */
    public function addChoices(array $choices)
    {
        $this->choices = $choices;
    }

    /**
     * @return array
     */
    public function choices()
    {
        return $this->choices;
    }

    /**
     * @return boolean
     */
    public function hasChoices()
    {
        return !empty($this->choices);
    }
}
