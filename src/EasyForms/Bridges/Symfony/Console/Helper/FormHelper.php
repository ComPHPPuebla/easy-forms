<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Symfony\Console\Helper;

use EasyForms\CodeGeneration\Forms\FormMetadata;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class FormHelper extends Helper
{
    /** @var array */
    protected $elements;

    /** @var ChoiceQuestion */
    protected $typeQuestion;

    /** @var Question */
    protected $nameQuestion;

    /** @var ConfirmationQuestion */
    protected $moreElementsQuestion;

    /**
     * Initialize questions to be asked to user when building a form
     *
     * @param array $elementTypes
     */
    public function __construct(array $elementTypes)
    {
        $this->elements = [];
        $this->nameQuestion = new Question("\n<question>What is the name of your element?</question>\n> ");
        $this->typeQuestion = new ChoiceQuestion(
            '<question>What kind of element do you want to add?</question>', $elementTypes
        );
        $this->moreElementsQuestion = new ConfirmationQuestion(
            "<question>Do you want to add another element (y/n)?</question> \n", false
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    public function addElements(InputInterface $input, OutputInterface $output)
    {
        $this->addElement($input, $output);
        while ($this->moreElements($input, $output)) {
            $this->addElement($input, $output);
            $output->writeln('');
        }

        return $this->elements;
    }

    /**
     * Ask the user the name and type of the element to be added to the form
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function addElement(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questions */
        $question = $this->getHelperSet()->get('question');

        $name = $question->ask($input, $output, $this->nameQuestion);
        $type = $question->ask($input, $output, $this->typeQuestion);

        $this->elements[$name] = $type;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    protected function moreElements(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questions */
        $question = $this->getHelperSet()->get('question');

        return $question->ask($input, $output, $this->moreElementsQuestion);
    }

    /**
     * @param FormMetadata $metadata
     * @param OutputInterface $output
     */
    public function showForm(FormMetadata $metadata, OutputInterface $output)
    {
        $output->writeln("\n<comment>{$metadata->className()} generated at:</comment>");
        $output->writeln("<info>{$metadata->classFilename()}</info>");
        $output->writeln("\n<comment>With code:</comment>");
        $output->writeln("<info>{$metadata->code()}</info>\n");
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'form';
    }
}
