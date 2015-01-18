<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use Twig_Template as Template;
use Twig_Environment as Environment;

class FormTheme
{
    /** @var Environment */
    protected $environment;

    /** @var string[] */
    protected $paths;

    /** @var Template[] */
    protected $templates = [];

    /** @var Template[] */
    protected $cache = [];

    /**
     * @param Environment $environment
     * @param array $themePaths
     */
    public function __construct(Environment $environment, array $themePaths)
    {
        $this->environment = $environment;
        $this->paths = $themePaths;
    }

    /**
     * @param Template $template
     */
    public function addTemplate(Template $template)
    {
        $this->templates[$template->getTemplateName()] = $template;

        /** @var Template $parent */
        if ($parent = $template->getParent([])) {
            $this->addTemplate($parent);
        }
    }

    /**
     * @param string $block
     * @return Template
     * @throws BlockNotFoundException
     */
    public function loadTemplateFor($block)
    {
        $this->loadTemplates();

        if (isset($this->cache[$block])) {
            return $this->cache[$block];
        }

        foreach ($this->templates as $template) {
            if ($template = $this->tryToCache($template, $block)) {
                break; // Block is defined in this template
            }
        }

        if (!$template) {
            throw new BlockNotFoundException("Block '$block' is not defined in the templates of this theme.");
        }

        return $template;
    }

    /**
     * @param Template $theme
     * @param string $block
     * @return Template | null
     */
    protected function tryToCache(Template $theme, $block)
    {
        if ($theme->hasBlock($block)) {
            $this->cache[$block] = $theme;

            return $theme;
        }
    }

    /**
     * Lazy load all the registered templates
     */
    protected function loadTemplates()
    {
        foreach ($this->paths as $path) {
            $this->loadTemplateForPath($path);
        }
    }

    /**
     * @param string $path
     */
    protected function loadTemplateForPath($path)
    {
        if (!isset($this->templates[$path])) {
            /** @var Template $template */
            $template = $this->environment->loadTemplate($path);
            $this->addTemplate($template);
        }
    }
}
