<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template\Pattern;

/**
 * Pattern Partial class.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class PatternPartial extends PatternAbstract
{
    /**
     * Search & replace current defined pattern for template.
     * Example of template:
     *
     * <code>
     * # In Template:
     * #
     * # {% partial: Path/To/Template %}
     * # {% partial: Path/To/Template, $var1, $var2, $var3 %}
     * #
     * # In Compiled template:
     *
     * <?php Template::partial('Path/To/Template', array()); ?>
     * <?php Template::partial('Path/To/Template', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3)); ?>
     *
     * </code>
     *
     * @return string Compiled template
     */
    public function render()
    {
        $pattern = '`{% ?partial:([^,%]+)([^%]*)%}`is';
        $replace = array();

        if ((bool) preg_match_all($pattern, $this->templateContent, $matches)) {

            foreach ($matches[0] as $index => $replaceString) {
                $template = '\'' . trim($matches[1][$index]) . '\'';
                $params   = (!empty($matches[2][$index]) ? trim($matches[2][$index], ' ,()') : '');
                $tmp      = explode(',', $params);
                $params   = array();
                if (!empty($params)) {
                    foreach ($tmp as $param) {
                        $params[] = '\'' . substr(trim($param), 1) . '\' => ' . trim($param);
                    }
                }

                $params             = implode(',', $params);
                $replace[$replaceString] = '<?=Template::partial(' . $template . ', array(' . $params . ')); ?>';
            }

            $this->templateContent = trim(str_replace(array_keys($replace), $replace, $this->templateContent));
        }

        return $this->templateContent;
    }

}