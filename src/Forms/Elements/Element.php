<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\Elements;

use Assert\Assertion;
use Forms\View\ElementView;

abstract class Element
{
    /** @var string */
    protected $name;

    /** @var array */
    protected $attributes = [];

    /** @var mixed */
    protected $value;

    /** @var array */
    protected $messages = [];

    /** @var boolean */
    protected $isRequired = true;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        Assertion::string($name);
        Assertion::notBlank($name);

        $this->attributes['name'] = $name;
        $this->name = $name;
    }

    /**
     * Make this element optional
     */
    public function makeOptional()
    {
        $this->isRequired = false;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages)
    {
        Assertion::allString($messages);
        $this->messages = $messages;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        if (!$view) {
            $view = new ElementView();
        }

        $view->attributes = $this->attributes;
        $view->messages = $this->messages;
        $view->valid = count($this->messages) === 0;
        $view->value = $this->value();
        $view->isRequired = $this->isRequired;
        $view->rowBlock = 'element_row';

        return $view;
    }
}
