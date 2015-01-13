<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\Forms\Bridges\SymfonyCsrf;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CsrfTokenProviderSpec extends ObjectBehavior
{
    function it_should_get_the_csrf_token_value(CsrfTokenManager $tokenManager, CsrfToken $token)
    {
        $tokenManager->getToken('_csrf_login')->willReturn($token);

        $this->beConstructedWith($tokenManager);
        $this->getToken('_csrf_login');

        $token->getValue()->shouldHaveBeenCalled();
    }

    function it_should_verify_if_the_submitted_token_is_valid(CsrfTokenManager $tokenManager)
    {
        $this->beConstructedWith($tokenManager);
        $this->isTokenValid('_csrf_login', '123456abce7');

        $tokenManager->isTokenValid(Argument::type(CsrfToken::class))->shouldHaveBeenCalled();
    }

    function it_should_refresh_the_value_of_a_token(CsrfTokenManager $tokenManager, CsrfToken $token)
    {
        $tokenManager->refreshToken('_csrf_login')->willReturn($token);

        $this->beConstructedWith($tokenManager);
        $this->refreshToken('_csrf_login');

        $token->getValue()->shouldHaveBeenCalled();
    }

    function it_should_remove_the_value_of_a_token(CsrfTokenManager $tokenManager)
    {
        $this->beConstructedWith($tokenManager);
        $this->removeToken('_csrf_login');

        $tokenManager->removeToken('_csrf_login')->shouldHaveBeenCalled();
    }
}
