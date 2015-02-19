<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
use EasyForms\Bridges\Symfony\Console\Command\GenerateFormCommand;
use EasyForms\Bridges\Symfony\Console\Helper\FormHelper;
use EasyForms\CodeGeneration\Forms\FormGenerator;
use EasyForms\CodeGeneration\Forms\FormMetadata;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Twig;

$paths = [
    getcwd() . '/vendor/autoload.php',
    getcwd() . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
];
$composerAutoLoaderFound = false;
foreach ($paths as $autoLoaderPath) {
    if (file_exists($autoLoaderPath)) {
        require $autoLoaderPath;
        $composerAutoLoaderFound = true;
        break;
    }
}

if (!$composerAutoLoaderFound) {
    fwrite(STDERR,
        'You must set up the project dependencies first. Use the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
    exit(1);
}

$application = new Application('EasyForms Console Tool', '1.0@dev');

$metadata = new FormMetadata();
$application
    ->getHelperSet()
    ->set(new FormHelper($metadata->elementTypes()))
;
$application->add(
    new GenerateFormCommand($metadata, new FormGenerator(new Twig(new Loader()), new Filesystem()))
);
$application->run();
