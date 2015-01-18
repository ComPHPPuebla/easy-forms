<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
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
            'cache' => __DIR__ . '/cache',
        ]);

        $this->renderer = new FormRenderer($this->environment, [$path]);

        $this->environment->addExtension(new FormExtension($this->renderer));
    }
}
