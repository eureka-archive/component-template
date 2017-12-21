<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template;

use Eureka\Component\Template\Pattern;

/**
 * Template class
 *
 * @author Romain Cottard
 */
class Template implements TemplateInterface
{
    /**
     * @var array $vars List of variables for templates
     */
    protected $vars = array();

    /**
     * @var Pattern\PatternCollection $patternCollection Collection of patterns
     */
    protected $patternCollection = null;

    /**
     * @var string $file Template file.
     */
    protected $file = '';

    /**
     * @var string $fileCompiled Template file compiled.
     */
    protected $fileCompiled = '';

    /**
     * @var string $pathModule Path to module templates.
     */
    protected static $pathModule = '';

    /**
     * @var string $pathCompiled Path for template compiled
     */
    protected static $pathCompiled = '/tmp/Eureka/tpl/';

    /**
     * @var bool If force compilation
     */
    protected static $forceCompilation = false;

    /**
     * Class constructor.
     *
     * @param  string                    $template
     * @param  array                     $vars
     * @param  Pattern\PatternCollection $collection
     * @throws \Exception
     */
    public function __construct($template, $collection = null, array $vars = array())
    {
        $this->file         = $template . '.tpl';
        $this->vars         = $vars;
        $this->fileCompiled = static::$pathCompiled . substr(md5($this->file), 0, 10) . '.php';

        if ($collection instanceof Pattern\PatternCollection) {
            $this->patternCollection = $collection;
        } else {
            $this->patternCollection = new Pattern\PatternCollection();
            $this->patternCollection->add(new Pattern\PatternBlock());
            $this->patternCollection->add(new Pattern\PatternPartial());
            $this->patternCollection->add(new Pattern\PatternNamespace());
            $this->patternCollection->add(new Pattern\PatternEcho());
            $this->patternCollection->add(new Pattern\PatternMain());
        }

        if (!file_exists($this->file)) {
            throw new \Exception('Template file does not exist ! (template: "' . $this->file . '")');
        }

        if (!file_exists($this->fileCompiled) || static::$forceCompilation) {
            $this->compile();
        }
    }

    /**
     * Transform template file into a php file.
     *
     * @return void
     * @throws \Exception
     */
    protected function compile()
    {
        $templateContent = file_get_contents($this->file);

        foreach ($this->patternCollection as $pattern) {
            $templateContent = $pattern->setContent($templateContent)
                ->render();
        }

        if (false === file_put_contents($this->fileCompiled, $templateContent)) {
            throw new \Exception('Cannot write compiled template file!');
        }
    }

    /**
     * Render Template.
     *
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        //~ Transform $this->vars into multiples variables.
        extract($this->vars);

        //~ Include Template as regular php file
        ob_start();
        include $this->fileCompiled;
        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    /**
     * Set Template var.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function setVar($name, $value = null)
    {
        $this->vars[$name] = $value;

        return $this;
    }

    /**
     * Appends vars to existing Template vars.
     *
     * @param  array $vars
     * @return self
     */
    public function appendVars(Array $vars)
    {
        $this->vars += $vars;

        return $this;
    }

    /**
     * Set Template vars.
     *
     * @param  array $vars
     * @return self
     */
    public function setVars(Array $vars)
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * Include partial.
     *
     * @param  string $template
     * @param  array  $vars
     * @return string Content
     * @throws \Exception
     */
    public static function partial($template, array $vars = array())
    {
        $template = new Template($template, null, $vars);

        return trim($template->render());
    }

    /**
     * Set path for compiled templates.
     *
     * @param  string $pathCompiled
     * @return void
     * @throws \Exception
     */
    public static function setPathCompiled($pathCompiled = '/tmp/Eureka/tpl')
    {
        $pathCompiled = rtrim($pathCompiled, '/') . '/';

        if (!file_exists($pathCompiled) || !is_dir($pathCompiled)) {
            if (!mkdir($pathCompiled, 0755, true)) {
                throw new  \Exception('Cannot create directory for templates compiled (path: ' . $pathCompiled . ')');
            }
        }

        static::$pathCompiled = $pathCompiled;
    }

    /**
     * Set if force compilation of template
     *
     * @param  bool $forceCompilation
     * @return void
     */
    public static function setForceCompilation($forceCompilation = true)
    {
        static::$forceCompilation = $forceCompilation;
    }
}
