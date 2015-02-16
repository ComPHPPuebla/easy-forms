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

    /** @var string */
    protected $namespace;

    /**
     * Keys contain the elements names and the values contain the elements' FQCNs
     *
     * @var array
     */
    protected $elements = [];

    /** @var array */
    protected $types;


    public function __construct()
    {
        $this->types = [
            'text' => new ClassMetadata(Text::class),
            'password' => new ClassMetadata(Password::class),
            'hidden' => new ClassMetadata(Hidden::class),
            'checkbox' => new ClassMetadata(Checkbox::class),
            'file' => new ClassMetadata(File::class),
        ];
    }


    /**
     * @param string $fullyQualifiedName
     */
    public function setClassName($fullyQualifiedName)
    {
        $parts = explode('\\', $fullyQualifiedName);
        $this->className = array_pop($parts);
        $this->namespace = implode('\\', $parts);
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
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function formNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return array
     */
    public function elements()
    {
        return $this->elements;
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
            $elements .= "{$name} of type {$class->fullyQualifiedName()}\n";
        }

        return "{$this->className} with the elements:\n{$elements}";
    }
}
