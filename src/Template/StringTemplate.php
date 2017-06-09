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
class StringTemplate
{
    /**
     * @var string $content
     */
    protected $content = '';

    /**
     * @var array $vars
     */
    protected $vars = [];

    /**
     * @var Pattern\PatternCollection $patternCollection
     */
    protected $patternCollection = null;

    /**
     * Class constructor.
     *
     * @param  string                    $content
     * @param  array                     $vars
     * @param  Pattern\PatternCollection $collection
     * @throws \Exception
     */
    public function __construct($content, $collection = null, array $vars = array())
    {
        $this->content = $content;
        $this->vars    = $vars;

        if ($collection instanceof Pattern\PatternCollection) {
            $this->patternCollection = $collection;
        } else {
            $this->patternCollection = new Pattern\PatternCollection();
            $this->patternCollection->add(new Pattern\PatternEcho());
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
        foreach ($this->patternCollection as $pattern) {
            $this->content = $pattern->setContent($this->content)
                ->render();
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
        $this->compile();

        //~ Transform $this->vars into multiples variables.
        //extract($this->vars);

        //~ Include Template as regular php file
        //ob_start();
        //echo $this->content;
        //$content = ob_get_clean();

        return $this->content;
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
}
