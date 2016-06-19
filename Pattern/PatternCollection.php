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
 * @version 2.1.0
 */
class PatternCollection implements \Iterator
{
    /**
     * Length of the collection
     * @var integer $length
     */
    protected $length = 0;

    /**
     * Current position of the cursor in collection.
     * @var integer
     */
    protected $index  = 0;

    /**
     * Collection of patterns.
     * @var Pattern[] $collection
     */
    protected $collection = array();

    /**
     * PatternCollection constructor.
     *
     * @return PatternCollection
     */
    public function __construct()
    {
        $this->collection = array();
    }

    /**
     * Add pattern object to the collection.
     *
     * @param Pattern $pattern
     * @return PatternCollection
     */
    public function add(Pattern $pattern)
    {
        $this->collection[$this->length] = $pattern;
        $this->length++;

        return $this;
    }

    /**
     * Get length of the collection.
     * @return integer
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * Get current pattern
     *
     * @return Pattern
     */
    public function current()
    {
        return $this->collection[$this->index];
    }

    /**
     * Reset internal cursor.
     *
     * @return Pattern
     */
    public function reset()
    {
        $this->index = 0;

        return $this;
    }

    /**
     * Get current key.
     * @return integer
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
     * @return PatternCollection
     */
    public function rewind()
    {
        $this->index = 0;

        return $this;
    }

    /**
     * Check if have more pattern in the collection
     * @return boolean
     */
    public function valid()
    {
        return ($this->index < $this->length);
    }

}