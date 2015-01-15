<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Elements\Csrf;

interface TokenProvider
{
    /**
     * @param string $tokenId
     * @return string
     */
    public function getToken($tokenId);

    /**
     * @param string $tokenId
     * @return string
     */
    public function refreshToken($tokenId);

    /**
     * @param string $tokenId
     */
    public function removeToken($tokenId);

    /**
     * @param string $tokenId
     * @param string $token
     * @return boolean
     */
    public function isTokenValid($tokenId, $token);
}
