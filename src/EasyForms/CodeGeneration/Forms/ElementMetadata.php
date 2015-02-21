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
    /** @var string */
    protected $elementName;

    /** @var array */
    protected $choices = [];

    /** @var boolean */
    protected $isOptional = false;

    /** @var boolean */
    protected $multipleSelection = false;

    /**
     * @param string $name
     */
    public function setElementName($name)
    {
        $this->elementName = $name;
    }

    /**
     * @param array $options
     */
    public function configure(array $options)
    {
        isset($options['choices']) && $this->choices = $options['choices'];
        $this->isOptional = $options['optional'];
        $this->multipleSelection = $options['multipleSelection'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $choices = '';
        !empty($this->choices) && $choices= ", {$this->formatChoices()}";

        $element = "new {$this->getShortName()}('{$this->elementName}'{$choices})";

        $this->isOptional && $element = "({$element})->makeOptional()";

        $this->multipleSelection && $element = "{$element}->enableMultipleSelection()";

        return $element;
    }

    /**
     * @return string
     */
    protected function formatChoices()
    {
        $choices = '[';
        foreach ($this->choices as $value => $label) {
            $choices .= "'{$value}' => '{$label}', ";
        }

        return trim($choices, ', ') . ']';
    }
}
