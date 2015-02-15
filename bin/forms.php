<?php
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

use EasyForms\Bridges\Symfony\Console\Command\GenerateFormCommand;
use EasyForms\Bridges\Symfony\Console\Helper\FormHelper;
use EasyForms\Bridges\Symfony\Console\Metadata\FormMetadata;
use Symfony\Component\Console\Application;

$application = new Application('EasyForms Console Tool', '1.0@dev');
$application->getHelperSet()->set(new FormHelper(new FormMetadata()));
$application->add(new GenerateFormCommand());
$application->run();
