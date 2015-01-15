<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Validation;

use EasyForms\Form;

interface FormValidator
{
    /**
     * @param Form $form
     * @return boolean
     */
    public function validate(Form $form);
}
