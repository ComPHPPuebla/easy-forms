<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace Forms\Elements;

use Forms\Elements\Captcha\CaptchaAdapter;
use Forms\View\ElementView;

class Captcha extends Element
{
    /** @var CaptchaAdapter */
    protected $captchaAdapter;

    /**
     * @param string $name
     * @param CaptchaAdapter $captchaAdapter
     */
    public function __construct($name, CaptchaAdapter $captchaAdapter)
    {
        parent::__construct($name);
        $this->captchaAdapter = $captchaAdapter;
    }

    /**
     * @param ElementView $view
     * @return CaptchaView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();

        /** @var ElementView $view */
        $view = parent::buildView($view);

        $view->options = array_merge([
            'captcha_id' => $this->captchaAdapter->generateId(),
            'word' => $this->captchaAdapter->word(),
        ], $this->captchaAdapter->options());

        $view->value = $view->options['captcha_id'];
        $view->block = "captcha_{$this->captchaAdapter->name()}";

        return $view;
    }
}