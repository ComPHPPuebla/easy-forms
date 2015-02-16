<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Symfony\Console\Metadata;

class ClassMetadata
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $fullyQualifiedName;

    /**
     * @param string $fullyQualifiedName
     */
    public function __construct($fullyQualifiedName)
    {
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->setName($fullyQualifiedName);
    }

    /**
     * @param string $fullyQualifiedName
     */
    protected function setName($fullyQualifiedName)
    {
        $parts = explode('\\', $fullyQualifiedName);
        $this->name = array_pop($parts);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function fullyQualifiedName()
    {
        return $this->fullyQualifiedName;
    }
}
