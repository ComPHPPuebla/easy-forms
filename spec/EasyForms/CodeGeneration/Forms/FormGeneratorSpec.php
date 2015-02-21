<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace spec\EasyForms\CodeGeneration\Forms;

use EasyForms\CodeGeneration\Forms\FormMetadata;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment as Twig;
use Twig_Loader_Filesystem as Loader;

class FormGeneratorSpec extends ObjectBehavior
{
    function it_should_generate_code_for_the_given_metadata(Twig $view, Filesystem $fileSystem, Loader $loader)
    {
        $view->getLoader()->willReturn($loader);
        $this->beConstructedWith($view, $fileSystem);
        $formMetadata = new FormMetadata();
        $formMetadata->populate('My\Awesome\LoginForm', [
            'username' => ['type' => 'text', 'optional' => false, 'multipleSelection' => false, 'value' => null],
            'languages' => ['type' => 'select', 'optional' => true, 'multipleSelection' => true, 'value' => null],
            'remember_me' => ['type' => 'checkbox', 'optional' => true, 'multipleSelection' => false, 'value' => null],
        ]);
        $formMetadata->setTargetDirectory('src/');

        $view->render('templates/class.php.twig', [
            'form' => $formMetadata,
        ])->willReturn($code = <<<CODE
<?php
namespace My\Awesome;

use EasyForms\Elements\Text;
use EasyForms\Elements\Select;
use EasyForms\Elements\Checkbox;
use EasyForms\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        \$this
            ->add(new Text('username'))
            ->add((new Select('languages'))->makeOptional()->enableMultipleSelection())
            ->add((new Checkbox('remember_me', 'remember'))->makeOptional())
        ;
    }
}
CODE
);

        $this->generate($formMetadata);

        $fileSystem->mkdir('src/My/Awesome')->shouldHaveBeenCalled();
        $fileSystem->dumpFile('src/My/Awesome/LoginForm.php', $code)->shouldHaveBeenCalled();
    }
}
