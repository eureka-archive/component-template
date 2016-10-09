<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template\Pattern;

/**
 * Pattern Collection class.
 *
 * @author Romain Cottard
 */
class PatternCollection implements \Iterator
{
    /**
     * @var int $length Length of the collection
     */
    protected $length = 0;

    /**
     * @var int $index Current position of the cursor in collection.
     */
    protected $index = 0;

    /**
     * @var PatternInterface[] $collection Collection of patterns.
     */
    protected $collection = array();

    /**
     * PatternCollection constructor.
     */
    public function __construct()
    {
        $this->collection = array();
    }

    /**
     * Add pattern object to the collection.
     *
     * @param  PatternInterface $pattern
     * @return self
     */
    public function add(PatternInterface $pattern)
    {
        $this->collection[$this->length] = $pattern;
        $this->length++;

        return $this;
    }

    /**
     * Get length of the collection.
     *
     * @return int
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * Get current pattern
     *
     * @return PatternInterface
     */
    public function current()
    {
        return $this->collection[$this->index];
    }

    /**
     * Get current key.
     *
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Go to the next pattern
     *
     * @return PatternCollection
     */
    public function next()
    {
        $this->index++;

        return $this;
    }

    /**
     * Go to the previous pattern.
     *
     * @return PatternCollection
     */
    public function rewind()
    {
        $this->index = 0;

        return $this;
    }

    /**
     * Check if have more pattern in the collection
     *
     * @return bool
     */
    public function valid()
    {
        return ($this->index < $this->length);
    }
}
