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
use EasyForms\CodeGeneration\Forms\FormGenerator;
use EasyForms\CodeGeneration\Forms\FormMetadata;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFormCommand extends Command
{
    /** @var FormMetadata */
    protected $metadata;

    /** @var FormGenerator */
    protected $generator;

    public function __construct(FormMetadata $metadata, FormGenerator $generator)
    {
        parent::__construct();
        $this->metadata = $metadata;
        $this->generator = $generator;
    }

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

        $output->writeln("\n<comment>Generating the code for:</comment> <info>{$input->getArgument('class')}</info>\n");

        $this->metadata->populate($input->getArgument('class'), $formHelper->addElements($input, $output));
        $this->metadata->setTargetDirectory($input->getArgument('directory'));

        $this->generator->generate($this->metadata);

        $formHelper->showForm($this->metadata, $output);
    }
}
