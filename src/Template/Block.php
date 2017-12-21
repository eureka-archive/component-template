<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template;

/**
 * Block template class
 *
 * @author Romain Cottard
 */
class Block
{
    /** @var Block $instance Class instance */
    protected static $instance = null;

    /** @var string[] $blocks List of blocks html */
    protected $blocks = [];

    /** @var string[] $hashCache Cached list of hash. Avoid double append when block has been already rendered. */
    protected $hashCache = [];

    /**
     * Block constructor.
     */
    protected function __construct()
    {
        $this->blocks = array();
    }

    /**
     * Get current instance (singleton pattern).
     *
     * @return Block
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            $className        = __CLASS__;
            static::$instance = new $className();
        }

        return static::$instance;
    }

    /**
     * Get block content.
     *
     * @param  string $name
     * @return string
     */
    public function get($name)
    {
        if (isset($this->blocks[$name])) {
            return $this->blocks[$name];
        } else {
            return '';
        }
    }

    /**
     * Check if has block
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Begin to catch block.
     *
     * @return void
     */
    public function begin()
    {
        ob_start();
    }

    /**
     * End to catch block and save it.
     *
     * @param  string $name
     * @param  bool   $append Append content to previous block. Otherwise, reset content.
     * @return void
     */
    public function end($name, $append = true)
    {
        $content = trim(ob_get_contents());
        ob_end_clean();

        $hash = md5($content);

        if (isset($this->hashCache[$hash])) {
            return;
        }

        $this->hashCache[$hash] = true;

        if (isset($this->blocks[$name]) && $append) {
            $this->blocks[$name] .= $content;
        } else {
            $this->blocks[$name] = $content;
        }
    }
}
