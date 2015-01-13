<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\Forms\Elements;

use Forms\Elements\Csrf\TokenProvider;
use PhpSpec\ObjectBehavior;

class CsrfSpec extends ObjectBehavior
{
    function it_should_use_the_token_generated_by_the_provider_as_the_element_value(TokenProvider $provider)
    {
        $this->beConstructedWith('csrf', $tokenId = '_csrf_token', $provider);
        $provider->getToken($tokenId)->willReturn($value = '11arf23lasd1');

        $view = $this->buildView();

        $view->value->shouldBe($value);
        $view->attributes['value']->shouldBe($value);
    }
}
