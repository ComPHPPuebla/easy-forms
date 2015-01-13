<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\View;

class ElementView
{
    /**
     * The form element HTML attributes
     *
     * @var array
     */
    public $attributes = [];

    /** @var mixed */
    public $value;

    /**
     * The form element error messages, if any
     *
     * @var array
     */
    public $messages = [];

    /** @var boolean */
    public $valid;

    /** @var boolean  */
    public $isRequired;

    /**
     * This attribute is used by form elements that need configuration options not related to HTML,
     * like CSRF elements and CAPTCHAS.
     *
     * @var array
     */
    public $options = [];

    /**
     * This attribute is used by form elements with choices, radio buttons and selects.
     *
     * @var array
     */
    public $choices = [];

    /**
     * The name of the block that will render the HTML for this element
     *
     * @var string
     */
    public $block;

    /**
     * The row block of a form element includes, its label, the element itself, and its error messages
     *
     * @var string
     */
    public $rowBlock;
}
