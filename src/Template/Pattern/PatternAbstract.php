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
 */
abstract class PatternAbstract implements PatternInterface
{
    /**
     * @var string $templateContent Template Content.
     */
    protected $templateContent = '';

    /**
     * Pattern constructor.
     *
     * @param  string $templateContent
     */
    public function __construct($templateContent = '')
    {
        $this->setContent($templateContent);
    }

    /**
     * Set template content
     *
     * @param  string $templateContent
     * @return self
     */
    public function setContent($templateContent)
    {
        $this->templateContent = $templateContent;

        return $this;
    }
}
