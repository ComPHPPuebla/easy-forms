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
     * Compiles the node to PHP.
     *
     * @param Compiler $compiler A Twig_Compiler instance
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'easy_forms\')->renderer()->addTheme(')
            ->subcompile($this->getNode('theme'))
            ->raw(");\n");
    }
}
