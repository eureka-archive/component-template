<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template\Pattern;

/**
 * Pattern Main class.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class PatternMain extends PatternAbstract
{
    /**
     * Search & replace current defined pattern for template.
     * Example of template:
     *
     * <code>
     * # In Template:
     *
     * <ul>
     * {# Comment in php tag #}
     * {{foreach($array as $index => $value):}}
     *     <li>{{@$index;}}: {{@$value;}}</li>
     * {{endforeach;}}
     * </ul>
     *
     * # In Compiled template:
     * <ul>
     * <?php /* Comment in php tag * / ?>
     * <?php foreach($array as $index => $value): ?>
     *     <li><?=$index;?>: <?=$value;?></li>
     * <?php endforeach; ?>
     * </ul>
     *
     * </code>
     *
     * @return string Compiled template
     */
    public function render()
    {
        $pattern = array(
            '{{'  => '<?php ',
            '}}'  => '?>',
            '{#'  => '<?php /* ',
            '#}'  => ' */ ?>',
        );

        return $this->templateContent = trim(str_replace(array_keys($pattern), $pattern, $this->templateContent));
    }

}