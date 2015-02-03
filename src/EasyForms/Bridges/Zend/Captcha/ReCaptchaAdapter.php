<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Zend\Captcha;

use EasyForms\Elements\Captcha\CaptchaAdapter;
use Zend\Captcha\ReCaptcha;
use ZendService\ReCaptcha\ReCaptcha as ReCaptchaService;

class ReCaptchaAdapter implements CaptchaAdapter
{
    /** @var ReCaptcha */
    protected $reCaptcha;

    /**
     * @param ReCaptcha $reCaptcha
     */
    public function __construct(ReCaptcha $reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->reCaptcha->generate();
    }

    /**
     * This name is used to identify a specific block to render
     *
     * @return string
     */
    public function name()
    {
        return 're_captcha';
    }

    /**
     * Configuration options that are needed to render a specific captcha.
     *
     * For example this captcha needs to expose its public key.
     *
     * @return array
     */
    public function options()
    {
        return [
            'host' => ReCaptchaService::API_SERVER,
            'public_key' => $this->reCaptcha->getPubkey(),
        ];
    }
}
