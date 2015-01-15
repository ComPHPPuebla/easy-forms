<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\View;

use ArrayObject;
use EasyForms\Elements\Element;

class FormView extends ArrayObject
{
    /** @var array */
    public $attributes = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements)
    {
        $elements = array_map(function (Element $element) {
            return $element->buildView();
        }, $elements);

        parent::__construct($elements);
        $this->setFlags(ArrayObject::ARRAY_AS_PROPS | ArrayObject::STD_PROP_LIST);
    }
}
