<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template\Pattern;

/**
 * Pattern Block class.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class PatternBlock extends PatternAbstract
{
    /**
     * Search & replace current defined pattern for template.
     * Example of template:
     *
     * <code>
     * # In Template:
     * #
     * # {%block:my_block_name%}
     * # <!-- Html / PHP code -->
     * # {%endblock%}
     * # {% block: my_another_block_name %}
     * # <!-- Html / PHP code -->
     * # {% endblock %}
     * #
     * # {%getblock:my_block_name%}
     * # In Compiled template:
     * <?php Block::getInstance()->begin(); ?>
     * <!-- Html / PHP code -->
     * <?php Block::getInstance()->end('my_block_name'); ?>
     *
     * <?php Block::getInstance()->begin(); ?>
     * <!-- Html / PHP code -->
     * <?php Block::getInstance()->end('my_another_block_name'); ?>
     *
     * </code>
     *
     * @return string Compiled template
     */
    public function render()
    {
        //~ Search block / endblock
        $pattern = '`{% ?block:([^%]+) ?%}(.*?){% ?endblock ?%}`is';
        $replace = array();

        if ((bool) preg_match_all($pattern, $this->templateContent, $matches)) {
            foreach ($matches[0] as $index => $string) {
                $block              = trim($matches[1][$index]);
                $content            = $matches[2][$index];
                $replace[$string] = '<?php Block::getInstance()->begin(); ?>' . $content . '<?php Block::getInstance()->end(\'' . addslashes($block) . '\'); ?>';
            }

            $this->templateContent = str_replace(array_keys($replace), $replace, $this->templateContent);
        }

        $pattern = '`{% ?getblock:([^%]+)%}`is';
        $replace = array();

        //~ Search for getblock
        if ((bool) preg_match_all($pattern, $this->templateContent, $matches)) {
            foreach ($matches[0] as $index => $replaceString) {
                $block              = trim($matches[1][$index]);
                $replace[$replaceString] = '<?=Block::getInstance()->get(\'' . addslashes($block) . '\'); ?>';
            }

            $this->templateContent = trim(str_replace(array_keys($replace), $replace, $this->templateContent));
        }

        return $this->templateContent;
    }

}