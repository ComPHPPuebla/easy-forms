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
use Zend\Captcha\Image;

class ImageCaptchaAdapter implements CaptchaAdapter
{
    /** @var Image */
    protected $imageCaptcha;

    /**
     * @param Image $imageCaptcha
     */
    public function __construct(Image $imageCaptcha)
    {
        $this->imageCaptcha = $imageCaptcha;
    }

    /**
     * @return string
     */
    public function generateId()
    {
        return $this->imageCaptcha->generate();
    }

    /**
     * @return string
     */
    public function word()
    {
        return $this->imageCaptcha->getWord();
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'image';
    }

    /**
     * @return array
     */
    public function options()
    {
        return [
            'image_url' => $this->imageCaptcha->getImgUrl(),
        ];
    }
}
