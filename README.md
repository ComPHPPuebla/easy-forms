# Easy form handling for PHP

Most popular PHP packages (like [Symfony][1] and [Zend][2]) provide support for the following tasks related to forms
processing.

* Rendering
* Validation
* Translation

However both packages require more dependencies, even for the simplest case (internationalization, validation,
and event dispatching packages for instance). For form rendering both packages need to use translation components.

This package is intended to be used as a glue for the packages that provide the above mentioned functionality.

Initially this package provides form rendering with Twig, validation with Zend input filter, CSRF tokens with Symfony
Security CSRF, captchas with Zend Captcha.

All these dependencies are optional and more adapters can be added to provide the same functionality with another
packages.

If you are already using the form components as part of its corresponding framework, you probably will not find this
package very useful. This package is intended to be used when you need a light integration and you don't need to install
more packages than you already have or need.

[1]: http://symfony.com/doc/current/components/form/introduction.html
[2]: http://framework.zend.com/manual/current/en/modules/zend.form.intro.html
