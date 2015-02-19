<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\CodeGeneration\Forms;

use ReflectionClass;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment as Twig;

class FormGenerator
{
    /** @var Twig */
    protected $view;

    /** @var FileSystem */
    protected $fileSystem;

    /**
     * @param Twig $view
     * @param Filesystem $fileSystem
     */
    public function __construct(Twig $view, Filesystem $fileSystem)
    {
        $class = new ReflectionClass($this);
        $view->getLoader()->addPath(dirname($class->getFileName()) . '/../Resources');
        $this->view = $view;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param FormMetadata $formMetadata
     * @return string
     */
    public function generate(FormMetadata $formMetadata)
    {
        $formMetadata->setCode($this->view->render('templates/class.php.twig', [
            'form' => $formMetadata,
        ]));
        $this->write($formMetadata);
    }

    /**
     * @param FormMetadata $formMetadata
     */
    public function write(FormMetadata $formMetadata)
    {
        $this->fileSystem->mkdir($formMetadata->classDirectory());
        $this->fileSystem->dumpFile($formMetadata->classFilename(), $formMetadata->code());
    }
}
