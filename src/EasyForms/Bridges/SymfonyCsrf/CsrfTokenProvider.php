<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\SymfonyCsrf;

use EasyForms\Elements\Csrf\TokenProvider;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CsrfTokenProvider implements TokenProvider
{
    /** @var CsrfTokenManager */
    protected $tokenManager;

    /**
     * @param CsrfTokenManager $tokenManager
     */
    function __construct(CsrfTokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param string $tokenId
     * @return string
     */
    public function getToken($tokenId)
    {
        return $this->tokenManager->getToken($tokenId)->getValue();
    }

    /**
     * @param string $tokenId
     * @param string $token
     * @return bool
     */
    public function isTokenValid($tokenId, $token)
    {
        return $this->tokenManager->isTokenValid(new CsrfToken($tokenId, $token));
    }

    /**
     * @param string $tokenId
     * @return string
     */
    public function refreshToken($tokenId)
    {
        return $this->tokenManager->refreshToken($tokenId)->getValue();
    }

    /**
     * @param string $tokenId
     */
    public function removeToken($tokenId)
    {
        $this->tokenManager->removeToken($tokenId);
    }
}
