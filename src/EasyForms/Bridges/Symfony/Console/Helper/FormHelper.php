<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Symfony\Console\Helper;

use EasyForms\Bridges\Symfony\Console\Metadata\FormMetadata;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class FormHelper extends Helper
{
    /** @var FormMetadata */
    protected $formMetadata;

    /** @var ChoiceQuestion */
    protected $elementType;

    /** @var Question */
    protected $elementName;

    /** @var ConfirmationQuestion */
    protected $moreElements;

    /**
     * @param FormMetadata $formMetadata
     */
    public function __construct(FormMetadata $formMetadata)
    {
        $this->formMetadata = $formMetadata;
        $this->elementName = new Question("What is the name of your element?\n> ");
        $this->elementType = new ChoiceQuestion(
            'What kind of element do you want to add?',
            ['text', 'textarea', 'hidden', 'password', 'select', 'radio', 'checkbox', 'captcha', 'csrf token'],
            0
        );
        $this->moreElements = new ConfirmationQuestion(
            "Do you want to add another element (<info>y/n</info>)? \n", false
        );
    }

    /**
     * @param string $fullyQualifiedName
     */
    public function setClassName($fullyQualifiedName)
    {
        $this->formMetadata->setClassName($fullyQualifiedName);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    public function moreElements(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questions */
        $questions = $this->getHelperSet()->get('question');

        return $questions->ask($input, $output, $this->moreElements);
    }

    /**
     * Ask the user the name and type of the element to be added to the form
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function addElement(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questions */
        $questions = $this->getHelperSet()->get('question');
        $this->formMetadata->addElement(
            $questions->ask($input, $output, $this->elementName),
            $questions->ask($input, $output, $this->elementType)
        );
    }

    /**
     * @return FormMetadata
     */
    public function metadata()
    {
        return $this->formMetadata;
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
