<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\CodeGeneration\Forms;

use EasyForms\Elements\Checkbox;
use EasyForms\Elements\File;
use EasyForms\Elements\Hidden;
use EasyForms\Elements\Password;
use EasyForms\Elements\Text;
use ReflectionClass;

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

    /** @var string */
    protected $code;

    /** @var string */
    protected $targetDirectory;

    /**
     * Initialize all the valid form elements
     */
    public function __construct()
    {
        $this->types = [
            'text' => new ReflectionClass(Text::class),
            'password' => new ReflectionClass(Password::class),
            'hidden' => new ReflectionClass(Hidden::class),
            'checkbox' => new ReflectionClass(Checkbox::class),
            'file' => new ReflectionClass(File::class),
        ];
    }

    /**
     * @param string $className
     * @param array $elements
     */
    public function populate($className, array $elements)
    {
        $this->setClassName($className);
        $this->addElements($elements);
    }

    /**
     * This method cannot use reflection because this class does not exist yet
     *
     * @param string $fullyQualifiedName
     */
    protected function setClassName($fullyQualifiedName)
    {
        $parts = explode('\\', $fullyQualifiedName);
        $this->className = array_pop($parts);
        $this->namespace = implode('\\', $parts);
    }

    /**
     * @param array $elements
     */
    protected function addElements(array $elements)
    {
        foreach ($elements as $name => $type) {
            $this->elements[$name] = $this->types[$type];
        }
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @param string $targetDirectory
     */
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return string
     */
    public function targetDirectory()
    {
        return $this->targetDirectory;
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
    public function classDirectory()
    {
        return $this->targetDirectory . str_replace('\\', '/', $this->namespace);
    }

    /**
     * @return string
     */
    public function classFilename()
    {
        return "{$this->classDirectory()}/{$this->className}.php";
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
}
