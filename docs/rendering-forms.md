# Rendering forms

This package uses Twig and is heavily inspired by the way the Symfony2 form
component renders a form. Form rendering can be customized through *themes*. A
theme is a set of Twig templates which contain *blocks* to customize the way a
form element is rendered.

A form element is rendered in three parts:

* A label
* The HTML element
* Its error messages, if any

This package has 2 built-in themes:

* The default layout, which groups form elements in divs
* The Bootstrap 3 layout which extends the default layout

In order to use the themes you have to register the `FormExtension` provided in
this package.

```php
use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;

$loader = new Twig_Loader([
    // Path to themes
    'vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig',
    'path/to/your/application/templates',
]);
$twig = new Twig_Environment($loader);
// use the Bootstrap 3 layout
$renderer = new FormRenderer(
    new FormTheme($twig, 'layouts/bootstrap.html.twig'),
    new BlockOptions()
);
$twig->addExtension(new FormExtension($renderer));
```
Once you have the form extension configured, you can pass your form to any Twig
template.

```php
$view->render('user/login.html.twig', [
    'form' => $loginForm->buildView(),
]);
```

The extension defines some functions, among the most important are `form_start`,
`form_end`, and `element_row`.

The `element_row` defines 2 parameters, the form element, and an associative array
of options. The options are not mandatory, and can be explained as follows:

* `label`. The element 's label
* `label_attr`. The label's HTML attributes
* `attr`. The elements HTML  attributes
* `options`. This one is used mainly to override the default blocks that render the
elements (label, element and error messages, among others).

In the following example we use the options to define the elements labels, and its
HTML IDs which could be used for client side validation, for instance.

```twig
{{ form_start(login) }}
{{ element_row(login.username, {'label': 'Username', 'attr': {'id': 'username'}}) }}
{{ element_row(login.password, {'label': 'Password', 'attr': {'id': 'password'}}) }}
<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-home"></span> Login
</button>
{{ form_end() }}
```

If you want to display a form element in a different way in only one template, you
need to do 3 things:

1. Add the template that renders the form to the current theme
2. Define a custom block for your element
3. Override the block in the `options` parameter when calling `element_row`

Suppose you have a `ProductForm` class with 3 form elements, a text element with the
name of the product, a text area with an optional description, and another text
element to enter a unit price. To add the current template to the theme you will need
to use the `form_theme` token and pass the value `self` as argument, you can add more
than one template this way, therefore the value should be inside an array.

```twig
{# Add this template to the theme #}
{% form_theme [_self] %}

{# Custom block #}
{%- block money -%}
    <div class="input-group"><div class="input-group-addon">$</div>
        {%- set options = options|merge({'block': 'input'}) -%}
        {{- element(element, attr, options) -}}
    <div class="input-group-addon">.00</div></div>
{%- endblock money -%}

{{ form_start(form) }}
{{ element_row(form.name, {'label': 'Name', 'attr': {'id': 'name'}}) }}

{# Override the element's default block, use 'money' instead #}
{{ element_row(form.unitPrice, {'label': 'Price', 'options': {'block': 'money'}}) }}

<button type="submit" class="btn btn-default">
    <span class="glyphicon glyphicon-th-list"></span> Add to catalog
</button>
{{ form_end() }}
```
