<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template\Pattern;

/**
 * Pattern interface for template compilation
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
interface Pattern
{

    /**
     * Search & replace current defined pattern for template.
     *
     * @return string Compiled template
     */
    public function render();

    /**
     * Set template content
     *
     * @param  string $templateContent
     * @return Pattern
     */
    public function setContent($templateContent);
}