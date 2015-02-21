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
use EasyForms\Elements\Checkbox;
use EasyForms\Elements\Choice;
use EasyForms\Elements\Select;
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

    /** @var \ReflectionClass[] */
    protected $elementTypes;

    /** @var ChoiceQuestion */
    protected $typeQuestion;

    /** @var Question */
    protected $nameQuestion;

    /** @var ConfirmationQuestion */
    protected $choicesQuestion;

    /** @var Question */
    protected $valueQuestion;

    /** @var Question */
    protected $labelQuestion;

    /** @var ConfirmationQuestion */
    protected $moreChoicesQuestion;

    /** @var ConfirmationQuestion */
    protected $moreElementsQuestion;

    /** @var ConfirmationQuestion */
    protected $isOptionalQuestion;

    /** @var ConfirmationQuestion */
    protected $multipleSelectionQuestion;

    /** @var Question */
    protected $checkboxValueQuestion;

    /**
     * Initialize questions to be asked to user when building a form
     *
     * @param array $elementTypes
     */
    public function __construct(array $elementTypes)
    {
        $this->elements = [];
        $this->elementTypes = $elementTypes;
        $this->nameQuestion = new Question("\n<question>Enter the element name</question>\n > ");
        $this->typeQuestion = new ChoiceQuestion('<question>What\'s its type?</question>', array_keys($elementTypes));
        $this->choicesQuestion = new ConfirmationQuestion(
            "<question>Do you want to add choices to this element (y/n)?</question> \n > ", false
        );
        $this->valueQuestion = new Question("\n<question>Enter the choice value</question>\n > ");
        $this->labelQuestion = new Question("\n<question>Enter the choice label</question>\n > ");
        $this->moreChoicesQuestion = new ConfirmationQuestion(
            "\n<question>Do you want to add another choice (y/n)?</question> \n > ", false
        );
        $this->moreElementsQuestion = new ConfirmationQuestion(
            "\n<question>Do you want to add another element (y/n)?</question>\n > ", false
        );
        $this->isOptionalQuestion = new ConfirmationQuestion("<question>Is it optional (y/n)?</question>\n > ");
        $this->multipleSelectionQuestion = new ConfirmationQuestion(
            "\n<question>Allow multiple selection (y/n)?</question>\n > "
        );
        $this->checkboxValueQuestion = new Question("\n<question>Enter the checkbox value</question>\n> ");
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
        /** @var QuestionHelper $question */
        $question = $this->getHelperSet()->get('question');

        $name = $question->ask($input, $output, $this->nameQuestion);
        $type = $question->ask($input, $output, $this->typeQuestion);

        $choices = [];
        if (in_array($type, ['checkbox multiple', 'select', 'radio']) &&
            $question->ask($input, $output, $this->choicesQuestion)
        ) {
            $choices = $this->addChoices($input, $output);
        }

        $isOptional = $this->isOptional($input, $output);

        $multipleSelection = false;
        if ($this->elementTypes[$type] === Select::class &&
            $question->ask($input, $output, $this->multipleSelectionQuestion)
        ) {
            $multipleSelection = true;
        }

        $checkboxValue = null;
        if ($this->elementTypes[$type] === Checkbox::class) {
            $checkboxValue = $question->ask($input, $output, $this->checkboxValueQuestion);
        }

        $this->elements[$name] = [
            'type' => $type,
            'choices' => $choices,
            'optional' => $isOptional,
            'multipleSelection' => $multipleSelection,
            'value' => $checkboxValue,
        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    protected function addChoices(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $question */
        $question = $this->getHelperSet()->get('question');

        $choices = [];
        $choices[$question->ask($input, $output, $this->valueQuestion)] = $question->ask(
            $input, $output, $this->labelQuestion
        );

        while ($question->ask($input, $output, $this->moreChoicesQuestion)) {
            $choices[$question->ask($input, $output, $this->valueQuestion)] = $question->ask(
                $input, $output, $this->labelQuestion
            );
        }

        return $choices;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    protected function moreElements(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $question */
        $question = $this->getHelperSet()->get('question');

        return $question->ask($input, $output, $this->moreElementsQuestion);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    protected function isOptional(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $question */
        $question = $this->getHelperSet()->get('question');

        return $question->ask($input, $output, $this->isOptionalQuestion);
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
