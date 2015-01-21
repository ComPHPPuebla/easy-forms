<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright Comunidad PHP Puebla 2015 (http://www.comunidadphppuebla.com)
 */
namespace EasyForms\Bridges\Twig;

use EasyForms\Bridges\Twig\Exception\BlockNotFoundException;
use Twig_Template as Template;
use Twig_Environment as Environment;

class FormTheme
{
    /** @var Environment */
    protected $environment;

    /** @var string */
    protected $path;

    /** @var Template[] */
    protected $templates = [];

    /** @var Template[] */
    protected $cache = [];

    /** @var  array */
    protected $blocks = [];

    /**
     * @param Environment $environment
     * @param string $themePath
     */
    public function __construct(Environment $environment, $themePath)
    {
        $this->environment = $environment;
        $this->path = $themePath;
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
     * @param string $path
     * @return Template
     */
    public function loadTemplate($path)
    {
        return $this->environment->loadTemplate($path);
    }

    /**
     * @return array
     */
    public function blocks()
    {
        if (!$this->blocks) {
            foreach ($this->templates as $template) {
                $this->blocks += $template->getBlocks();
            }
        }

        return $this->blocks;
    }

    /**
     * @param string $block
     * @return Template
     * @throws BlockNotFoundException
     */
    public function loadTemplateFor($block)
    {
        $this->initTemplate();

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
     * Lazy load the main template
     */
    protected function initTemplate()
    {
        if (!isset($this->templates[$this->path])) {
            $this->addTemplate($this->loadTemplate($this->path));
        }
    }
}
