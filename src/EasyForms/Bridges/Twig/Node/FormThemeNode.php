<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace EasyForms\Bridges\Twig\Node;

use Twig_Node as Node;
use Twig_Compiler as Compiler;

class FormThemeNode extends Node
{
    /**
     * @param Node $templates
     * @param integer $lineNumber
     * @param string $tag
     */
    public function __construct(Node $templates, $lineNumber, $tag = null)
    {
        parent::__construct(['templates' => $templates], [], $lineNumber, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * Adds the templates to the current form's theme
     *
     * @param Compiler $compiler A Twig_Compiler instance
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'easy_forms\')->renderer()->addTemplates(')
            ->subcompile($this->getNode('templates'))
            ->raw(");\n");
    }
}
