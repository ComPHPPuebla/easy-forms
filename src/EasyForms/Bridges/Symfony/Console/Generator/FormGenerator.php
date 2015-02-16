<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Symfony\Console\Generator;

use EasyForms\Bridges\Symfony\Console\Metadata\FormMetadata;
use ReflectionClass;
use Twig_Environment as Twig;

class FormGenerator
{
    /** @var Twig */
    protected $view;

    /**
     * @param Twig $view
     */
    public function __construct(Twig $view)
    {
        $class = new ReflectionClass($this);
        $view->getLoader()->addPath(dirname($class->getFileName()) . '/../Resources');
        $this->view = $view;
    }

    /**
     * @param FormMetadata $formMetadata
     * @return string
     */
    public function generate(FormMetadata $formMetadata)
    {
        return $this->view->render('templates/class.php.twig', [
            'form' => $formMetadata,
        ]);
    }
}
