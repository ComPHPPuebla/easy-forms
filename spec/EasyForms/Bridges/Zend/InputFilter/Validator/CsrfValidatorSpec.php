<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\Bridges\Zend\InputFilter\Validator;

use EasyForms\Elements\Csrf\TokenProvider;
use PhpSpec\ObjectBehavior;
use Assert\InvalidArgumentException;

class CsrfValidatorSpec extends ObjectBehavior
{
    function it_should_throw_exception_if_no_token_provider_is_provided()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [[]]);
    }

    function it_should_throw_exception_if_no_token_id_is_provided(TokenProvider $provider)
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [[
            'tokenProvider' => $provider,
        ]]);
    }

    function it_should_throw_exception_if_the_token_id_is_an_empty_string(TokenProvider $provider)
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [[
            'tokenProvider' => $provider,
            'tokenId' => '   ',
        ]]);
    }

    function it_should_not_pass_validation_if_an_invalid_token_is_provided(TokenProvider $provider)
    {
        $tokenId = '_login_csrf_token';
        $token = 'e95b24c1586e4b3dbfadcdd85aee46e0';
        $this->beConstructedWith([
            'tokenProvider' => $provider,
            'tokenId' => $tokenId,
        ]);

        $provider->isTokenValid($tokenId, $token)->willReturn(false);

        $isValid = $this->isValid($token);

        $isValid->shouldBe(false);
        $this->getMessages()->shouldBeArray();
    }

    function it_should_pass_validation_if_a_valid_token_is_provided(TokenProvider $provider)
    {
        $tokenId = '_login_csrf_token';
        $token = 'e95b24c1586e4b3dbfadcdd85aee46e0';
        $this->beConstructedWith([
            'tokenProvider' => $provider,
            'tokenId' => $tokenId,
        ]);

        $provider->isTokenValid($tokenId, $token)->willReturn(true);
        $provider->removeToken($tokenId)->shouldBeCalled();

        $isValid = $this->isValid($token);

        $isValid->shouldBe(true);
        $this->getMessages()->shouldHaveCount(0);
    }

    function it_should_update_token_after_failed_validation_when_the_option_is_set_to_true(TokenProvider $provider)
    {
        $tokenId = '_login_csrf_token';
        $token = 'e95b24c1586e4b3dbfadcdd85aee46e0';
        $this->beConstructedWith([
            'tokenProvider' => $provider,
            'tokenId' => $tokenId,
            'updateToken' => true,
        ]);

        $provider->isTokenValid($tokenId, $token)->willReturn(false);
        $provider->refreshToken($tokenId)->shouldBeCalled();

        $isValid = $this->isValid($token);

        $isValid->shouldBe(false);
        $this->getMessages()->shouldHaveCount(1);
    }
}
