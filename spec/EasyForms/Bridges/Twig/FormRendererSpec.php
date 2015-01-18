<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\Bridges\Twig;

use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormTheme;
use EasyForms\Elements\Captcha\CaptchaAdapter;
use EasyForms\Elements\Captcha;
use EasyForms\Elements\File;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Form;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Twig_Template as Template;

class FormRendererSpec extends ObjectBehavior
{
    function let(FormTheme $theme, BlockOptions $blockOptions)
    {
        $this->beConstructedWith($theme, $blockOptions);
    }

    function it_should_render_an_element(FormTheme $theme, Template $template)
    {
        $htmlAttributes = [
            'class' => 'js-highlighted'
        ];
        $username = new Text('username');
        $username->setValue('john.doe');
        $usernameView = $username->buildView();
        $theme->loadTemplateFor($usernameView->block)->willReturn($template);

        $this->renderElement($usernameView, $htmlAttributes);

        $template->displayBlock($usernameView->block, [
            'element' => $usernameView,
            'attr' => $usernameView->attributes + $htmlAttributes,
            'value' => $usernameView->value,
            'options' => [],
            'choices' => [],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_with_choices(FormTheme $theme, Template $template)
    {
        $htmlAttributes = [
            'class' => 'js-highlighted'
        ];
        $languages = new Select('languages');
        $languages->setChoices([
            'PHP' => 'PHP',
            'CSH' => 'C#',
            'JAV' => 'Java',
            'SCL' => 'Scala',
        ]);
        $languages->setValue('PHP');
        $languagesView = $languages->buildView();
        $theme->loadTemplateFor($languagesView->block)->willReturn($template);

        $this->renderElement($languagesView, $htmlAttributes);

        $template->displayBlock($languagesView->block, [
            'element' => $languagesView,
            'attr' => $languagesView->attributes + $htmlAttributes,
            'value' => $languagesView->value,
            'options' => [],
            'choices' => $languagesView->choices,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_with_options(FormTheme $theme, Template $template)
    {
        $captcha = new Captcha('captcha', new FakeCaptchaAdapter());
        $captcha->setValue('1325');
        $htmlAttributes = ['class' => 'js-highlighted'];
        $captchaOptions = ['image_attr' => ['id' => 'js-captcha']];
        $captchaView = $captcha->buildView();
        $theme->loadTemplateFor($captchaView->block)->willReturn($template);

        $this->renderElement($captchaView, $htmlAttributes, $captchaOptions);

        $template->displayBlock($captchaView->block, [
            'element' => $captchaView,
            'attr' => $captchaView->attributes + $htmlAttributes,
            'value' => $captchaView->value,
            'options' => $captchaView->options + $captchaOptions,
            'choices' => [],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_a_form_row(BlockOptions $blockOptions, FormTheme $theme, Template $template)
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
        $processedOptions = [
            'element' => $usernameView,
            'valid' => $usernameView->valid,
            'attr' => $usernameView->attributes + $options['attr'],
            'options' => [],
            'label' => $options['label'],
            'label_attr' => $options['label_attr'],
        ];
        $theme->loadTemplateFor($usernameView->rowBlock)->willReturn($template);
        $blockOptions->process($usernameView, $options)->willReturn($processedOptions);

        $this->renderRow($usernameView, $options);

        $template->displayBlock($usernameView->rowBlock, $processedOptions)->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_errors(FormTheme $theme, Template $template)
    {
        $username = new Text('username');
        $username->setMessages([
            'User "john.doe" does not exist.'
        ]);
        $usernameView = $username->buildView();
        $theme->loadTemplateFor('errors')->willReturn($template);

        $this->renderErrors($usernameView);

        $template->displayBlock('errors', [
            'errors' => $usernameView->messages,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_an_element_label(FormTheme $theme, Template $template)
    {
        $username = new Text('username');
        $labelAttributes = ['class' => 'js-label'];
        $elementId = 'username';
        $usernameView = $username->buildView();
        $theme->loadTemplateFor('label')->willReturn($template);

        $this->renderLabel($usernameView, 'Username', $elementId, $labelAttributes);

        $template->displayBlock('label', [
            'label' => 'Username',
            'attr' => $labelAttributes + ['for' => $elementId],
            'is_required' => $usernameView->isRequired,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_the_opening_tag_of_a_form(FormTheme $theme, Template $template)
    {
        $form = new Form();
        $form->add(new Text('username'));
        $formAttributes = ['name' => 'login'];
        $formView = $form->buildView();
        $theme->loadTemplateFor('form_start')->willReturn($template);

        $this->renderFormStart($formView, $formAttributes);

        $template->displayBlock('form_start', [
            'attr' => $formView->attributes + $formAttributes,
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_the_opening_tag_of_a_multipart_form(FormTheme $theme, Template $template)
    {
        $form = new Form();
        $form->add(new File('avatar'));
        $formView = $form->buildView();
        $theme->loadTemplateFor('form_start')->willReturn($template);

        $this->renderFormStart($formView);

        $template->displayBlock('form_start', [
            'attr' => $formView->attributes + ['enctype' => 'multipart/form-data'],
        ])->shouldHaveBeenCalled();
    }

    function it_should_render_the_closing_tag_of_a_form(FormTheme $theme, Template $template)
    {
        $theme->loadTemplateFor('form_end')->willReturn($template);

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
