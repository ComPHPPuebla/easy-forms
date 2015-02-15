<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Symfony\Console\Metadata;

use EasyForms\Elements\Checkbox;
use EasyForms\Elements\File;
use EasyForms\Elements\Hidden;
use EasyForms\Elements\Password;
use EasyForms\Elements\Text;

class FormMetadata
{
    /** @var string */
    protected $className;

    /**
     * Keys contain the elements names and the values contain the elements' FQCNs
     *
     * @var array
     */
    protected $elements = [];

    /** @var array */
    protected $types = [
        'text' => Text::class,
        'password' => Password::class,
        'hidden' => Hidden::class,
        'checkbox' => Checkbox::class,
        'file' => File::class,
    ];

    /**
     * @param string $fullyQualifiedName
     */
    public function setClassName($fullyQualifiedName)
    {
        $this->className = $fullyQualifiedName;
    }

    /**
     * @param string $name
     * @param string $type
     */
    public function addElement($name, $type)
    {
        $this->elements[$name] = $this->types[$type];
    }

    /**
     * @return array
     */
    public function elementTypes()
    {
        return array_keys($this->types);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $elements = '';
        foreach ($this->elements as $name => $class) {
            $elements .= "{$name} of type {$class}\n";
        }

        return "{$this->className} with the elements:\n{$elements}";
    }
}
