<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template\Pattern;

/**
 * Pattern abstract class for template
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
abstract class PatternAbstract implements Pattern
{
    /**
     * Template Content.
     *
     * @var string $templateContent
     */
    protected $templateContent = '';

    /**
     * Pattern constructor.
     *
     * @param  string $templateContent
     * @return PatternAbstract
     */
    public function __construct($templateContent = '')
    {
        $this->setContent($templateContent);
    }

    /**
     * Set template content
     * @param  string $templateContent
     * @return PatternAbstract
     */
    public function setContent($templateContent)
    {
        $this->templateContent = $templateContent;

        return $this;
    }
}
