<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Elements;

use Assert\Assertion;
use EasyForms\Elements\Csrf\TokenProvider;
use EasyForms\View\ElementView;

class Csrf extends Hidden
{
    /** @var TokenProvider */
    protected $tokenProvider;

    /** @var string */
    protected $tokenId;

    /**
     * @param string $name
     * @param string $tokenId
     * @param TokenProvider $tokenProvider
     */
    public function __construct($name, $tokenId, TokenProvider $tokenProvider)
    {
        parent::__construct($name);
        $this->setTokenId($tokenId);
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @param string $tokenId
     */
    protected function setTokenId($tokenId)
    {
        Assertion::string($tokenId);
        Assertion::notEmpty($tokenId);

        $this->tokenId = $tokenId;
    }

    /**
     * @param ElementView $view
     * @return ElementView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new ElementView();
        $view = parent::buildView($view);

        $this->setValue($this->tokenProvider->getToken($this->tokenId));

        // Setting the token value modifies the attributes and value properties
        $view->attributes = $this->attributes;
        $view->value = $this->value;

        return $view;
    }
}
