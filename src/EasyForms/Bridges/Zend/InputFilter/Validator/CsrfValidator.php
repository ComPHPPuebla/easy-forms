<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Zend\InputFilter\Validator;

use Assert\Assertion;
use EasyForms\Elements\Csrf\TokenProvider;
use Zend\Validator\AbstractValidator;

class CsrfValidator extends AbstractValidator
{
    /**
     * Error codes
     *
     * @const string
     */
    const NOT_SAME = 'notSame';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SAME => "The form submitted did not originate from the expected site",
    ];

    /** @var TokenProvider */
    protected $tokenProvider;

    /** @var string */
    protected $tokenId;

    /** @var boolean */
    protected $updateToken = false;

    /**
     * Constructor
     *
     * @param  array $options{
     *     @var array   $tokenProvider The token provider implementation
     *     @var string  $tokenId       The token identifier
     * }
     */
    public function __construct(array $options = [])
    {
        Assertion::keyExists(
            $options,
            'tokenProvider',
            'The "tokenProvider" option should be an instance of Forms\Elements\Csrf\TokenProvider'
        );
        Assertion::keyExists($options, 'tokenId', 'The "tokenId" option should be provided');

        parent::__construct($options);
    }

    /**
     * @param TokenProvider $tokenProvider
     */
    protected function setTokenProvider(TokenProvider $tokenProvider)
    {
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @param string $tokenId
     */
    protected function setTokenId($tokenId)
    {
        Assertion::string($tokenId);
        Assertion::notEmpty(trim($tokenId));

        $this->tokenId = $tokenId;
    }

    /**
     * @param boolean $updateToken
     */
    protected function setUpdateToken($updateToken)
    {
        Assertion::boolean($updateToken);

        $this->updateToken = $updateToken;
    }

    /**
     * Does the provided token match the one generated?
     *
     * @param  string $value
     * @param  mixed $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (!$this->tokenProvider->isTokenValid($this->tokenId, $value)) {
            $this->refreshToken($this->tokenId);
            $this->error(self::NOT_SAME);

            return false;
        }

        $this->tokenProvider->removeToken($this->tokenId);

        return true;
    }

    /**
     * Refresh token if 'updateToken' flag is set to true
     */
    protected function refreshToken()
    {
        if ($this->updateToken) {
            $this->tokenProvider->refreshToken($this->tokenId);
        }
    }
}
