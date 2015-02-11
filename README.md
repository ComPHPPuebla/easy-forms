# Easy form handling for PHP

[![Build Status](https://travis-ci.org/ComPHPPuebla/easy-forms.svg?branch=master)](https://travis-ci.org/ComPHPPuebla/easy-forms)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b21b5fac-a347-4c1a-b9b0-9965a93893ea/mini.png)](https://insight.sensiolabs.com/projects/b21b5fac-a347-4c1a-b9b0-9965a93893ea)

This is a light library to process HTML forms in Web applications. Its main
goal is to delegate concerns like, validation, rendering, translation, and
dynamic modification/population to well-known components.

This package is intended to be used as a glue for packages that provide the
following functionality.

* Rendering ([Twig][3])
* Validation ([Zend input filter][4])
* Special form elements like CSRF tokens ([Symfony Security CSRF][5]), and
Captcha elements ([Zend CAPTCHA][6]).

All these dependencies are *optional* and more adapters can be added to
provide the same functionality with other components.

The reason behind this package is that most popular PHP packages (like
[Symfony][1] and [Zend][2]) require you to install several dependencies,
like translation, validation, and event dispatching packages, even if you
don't need to use them.

If you are already using a form component as part of its corresponding framework,
you probably will not find this package very useful. This package is intended to
be used when you need a simple integration and you don't need or want to install
more packages than you already have.

## Examples

You can find some working examples in a small Slim application in this
[repository][7].


[1]: http://symfony.com/doc/current/components/form/introduction.html
[2]: http://framework.zend.com/manual/current/en/modules/zend.form.intro.html
[3]: http://twig.sensiolabs.org/
[4]: http://framework.zend.com/manual/current/en/modules/zend.input-filter.intro.html
[5]: https://github.com/symfony/security-csrf
[6]: http://framework.zend.com/manual/current/en/modules/zend.captcha.intro.html
[7]: https://github.com/MontealegreLuis/easy-forms-examples
