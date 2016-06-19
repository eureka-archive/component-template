<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template;

/**
 * Template interface
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
interface TemplateInterface
{
    /**
     * Render Template.
     *
     * @return  string
     * @throws \Exception
     */
    public function render();

}
