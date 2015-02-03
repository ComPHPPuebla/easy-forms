<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Zend\InputFilter;

use EasyForms\Form;
use EasyForms\Validation\FormValidator;
use Zend\InputFilter\InputFilter;

class InputFilterValidator implements FormValidator
{
    /** @var InputFilter */
    protected $inputFilter;

    /**
     * @param InputFilter $filter
     */
    public function __construct(InputFilter $filter)
    {
        $this->inputFilter = $filter;
    }

    /**
     * Validates the form data using the provided input filter.
     *
     * If validation errors are found, the form is populated with the corresponding error messages.
     * Form values are updated with the clean values provided by the filter.
     *
     * @param  Form $form
     * @return boolean
     */
    public function validate(Form $form)
    {
        $this->inputFilter->setData($form->values());

        if (!$isValid = $this->inputFilter->isValid()) {
            $form->setErrorMessages($this->inputFilter->getMessages());

            return $isValid;
        }

        $form->submit($this->inputFilter->getValues());

        return $isValid;
    }
}
