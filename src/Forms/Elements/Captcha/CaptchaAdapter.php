<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\Elements\Captcha;

interface CaptchaAdapter
{
    /**
     * @return string
     */
    public function generateId();

    /**
     * @return string
     */
    public function word();

    /**
     * This name is used to identify a specific block to render
     *
     * @return string
     */
    public function name();

    /**
     * Configuration options that are needed to render a specific captcha.
     *
     * For example the image captcha needs to expose the URL where the captcha images are stored.
     *
     * @return array
     */
    public function options();
}
