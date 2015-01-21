<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig\TokenParser;

use EasyForms\Bridges\Twig\Node\FormThemeNode;
use Twig_Node_Expression_Array as ArrayExpression;
use Twig_TokenParser as TokenParser;
use Twig_Token as Token;

class AddThemeTokenParser extends TokenParser
{
    /**
     * Parses a theme token and returns a form theme node.
     *
     * @param Token $token
     * @return \Twig_Node
     */
    public function parse(Token $token)
    {
        $lineNumber = $token->getLine();
        $stream = $this->parser->getStream();

        $templates = $this->parser->getExpressionParser()->parseExpression();

        $stream->expect(Token::BLOCK_END_TYPE);

        return new FormThemeNode($templates, $lineNumber, $this->getTag());
    }

    /**
     * @return string The form theme tag name
     */
    public function getTag()
    {
        return 'form_theme';
    }
}
