<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Symfony\Console\Command;

use EasyForms\Bridges\Symfony\Console\Helper\FormHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFormCommand extends Command
{
    /**
     * To create a form you must provide its FQCN
     */
    protected function configure()
    {
        $this
            ->setName('form:create')
            ->setDescription('Generate a form interactively')
            ->addArgument(
                'class', InputArgument::REQUIRED, 'The fully qualified name for your new form "Example\Forms\CoolForm"'
            )
            ->addArgument('directory', InputArgument::REQUIRED, 'The directory to which classes will be saved')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormHelper $formHelper */
        $formHelper = $this->getHelper('form');
        $formHelper->setClassName($class = $input->getArgument('class'));

        $output->writeln("Specify the elements for <info>$class</info>");

        $moreElements = true;
        while ($moreElements) {
            $formHelper->addElement($input, $output);
            $moreElements = $formHelper->moreElements($input, $output);
            $output->writeln('');
        }

        $output->writeln("Generating the form:\n<info>{$formHelper->metadata()}</info>");
        $output->writeln($classCode = $formHelper->generate());
        $formHelper->write($input->getArgument('path'), $classCode);
        $output->writeln('<info>Class successfully created.</info>');
    }
}
