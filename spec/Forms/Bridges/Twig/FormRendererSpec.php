<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\Forms\Bridges\Twig;

use Forms\Elements\Captcha\CaptchaAdapter;
use Forms\Elements\Captcha;
use Forms\Elements\File;
use Forms\Elements\Select;
use Forms\Elements\Text;
use Forms\Form;
use PhpSpec\ObjectBehavior;
use Twig_Environment as Environment;
use Twig_Template as Template;

class FormRendererSpec extends ObjectBehavior
{
    function let(Environment $environment, Template $template)
    {
        $this->beConstructedWith($environment, 'bootstrap3.html.twig');
        $environment->loadTemplate('bootstrap3.html.twig')->willReturn($template);
    }

    function it_should_render_an_element(Template $template)
    {
        $username = new Text('username');
        $username->setValue('john.doe');
        $usernameView = $username->buildView();

        $this->renderElement($usernameView, $htmlAttributes = [
            'class' => 'js-highlighted'
        ]);

        $template->displayBlock($usernameView->block, [
            'element' => $usernameView,
            'attr' => $usernameView->attributes + $htmlAttributes,
            'value' => $usernameView->value,
            'options' => [],
            'choices' => [],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_with_choices(Template $template)
    {
        $languages = new Select('languages');
        $languages->setChoices([
            'PHP' => 'PHP',
            'CSH' => 'C#',
            'JAV' => 'Java',
            'SCL' => 'Scala',
        ]);
        $languages->setValue('PHP');
        $languagesView = $languages->buildView();

        $this->renderElement($languagesView, $htmlAttributes = [
            'class' => 'js-highlighted'
        ]);

        $template->displayBlock($languagesView->block, [
            'element' => $languagesView,
            'attr' => $languagesView->attributes + $htmlAttributes,
            'value' => $languagesView->value,
            'options' => [],
            'choices' => $languagesView->choices,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_with_options(Template $template)
    {
        $captcha = new Captcha('captcha', new FakeCaptchaAdapter());
        $captcha->setValue('1325');
        $htmlAttributes = ['class' => 'js-highlighted'];
        $captchaOptions = ['image_attr' => ['id' => 'js-captcha']];
        $captchaView = $captcha->buildView();

        $this->renderElement($captchaView, $htmlAttributes, $captchaOptions);

        $template->displayBlock($captchaView->block, [
            'element' => $captchaView,
            'attr' => $captchaView->attributes + $htmlAttributes,
            'value' => $captchaView->value,
            'options' => $captchaView->options + $captchaOptions,
            'choices' => [],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_a_form_row(Template $template)
    {
        $username = new Text('username');
        $username->setValue('john.doe');
        $username->setMessages([
            'User "john.doe" does not exist.'
        ]);
        $options = [
            'attr' => [
                'class' => 'inline-element',
            ],
            'label' => 'Username',
            'label_attr' => [
                'class' => 'inline-label',
            ],
        ];
        $usernameView = $username->buildView();

        $this->renderRow($usernameView, $options);

        $template->displayBlock($usernameView->rowBlock, [
            'element' => $usernameView,
            'valid' => $usernameView->valid,
            'attr' => $usernameView->attributes + $options['attr'],
            'options' => [],
            'label' => $options['label'],
            'label_attr' => $options['label_attr'],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_errors(Template $template)
    {
        $username = new Text('username');
        $username->setMessages([
            'User "john.doe" does not exist.'
        ]);

        $usernameView = $username->buildView();
        $this->renderErrors($usernameView);

        $template->displayBlock('errors', [
            'errors' => $usernameView->messages,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_label(Template $template)
    {
        $username = new Text('username');
        $labelAttributes = ['class' => 'js-label'];
        $elementId = 'username';

        $usernameView = $username->buildView();
        $this->renderLabel($usernameView, 'Username', $elementId, $labelAttributes);

        $template->displayBlock('label', [
            'label' => 'Username',
            'attr' => $labelAttributes + ['for' => $elementId],
            'is_required' => $usernameView->isRequired,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_the_opening_tag_of_a_form(Template $template)
    {
        $form = new Form();
        $form->add(new Text('username'));
        $formAttributes = ['name' => 'login'];
        $formView = $form->buildView();

        $this->renderFormStart($formView, $formAttributes);

        $template->displayBlock('form_start', [
            'attr' => $formView->attributes + $formAttributes,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_the_opening_tag_of_a_multipart_form(Template $template)
    {
        $form = new Form();
        $form->add(new File('avatar'));
        $formView = $form->buildView();

        $this->renderFormStart($formView);

        $template->displayBlock('form_start', [
            'attr' => $formView->attributes + ['enctype' => 'multipart/form-data'],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_the_closing_tag_of_a_form(Template $template)
    {
        $this->renderFormEnd();

        $template->displayBlock('form_end', [])->shouldHaveBeenCalled();
    }
}

class FakeCaptchaAdapter implements CaptchaAdapter
{
    public function generateId() { return mt_rand(); }

    public function word() { return md5(mt_rand()); }

    public function name() { return 'fake'; }

    public function options() { return ['image_url' => '/images/capctha']; }
}
