<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

trait ProvidesLayoutConfiguration
{
    /** @var FormRenderer */
    protected $renderer;

    /** @var Environment */
    protected $environment;

    /**
     * @param string $path Relative path to layout file
     */
    protected function configureLayout($path)
    {
        $loader = new Loader([
            str_replace('tests', 'src', __DIR__)
        ]);
        $this->environment = new Environment($loader, [
            'debug' => true,
            'strict_variables' => true,
        ]);

        $this->renderer = new FormRenderer(new FormTheme($this->environment, $path));

        $this->environment->addExtension(new FormExtension($this->renderer));
    }
}
