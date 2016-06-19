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
class PatternNamespace extends PatternAbstract
{
    /**
     * Search & replace current defined pattern for template.
     * Example of template:
     *
     * <code>
     * # In Template:
     * #
     * # {* \Eureka\Component\Template: Template, PatternNamespace, PatternBlock *}
     * # {* \Eureka\Component\Http: Get *}
     * #
     * # In Compiled template:
     * <?php use \Eureka\Component\Template\Template; ?>
     * <?php use \Eureka\Component\Template\PatternNamespace; ?>
     * <?php use \Eureka\Component\Template\PatternBlock; ?>
     * <?php use \Eureka\Component\Http\Get; ?>
     *
     * </code>
     *
     * @return string Compiled template
     */
    public function render()
    {
        $pattern = '`{\*([^:]+):([^*]+)\*}`is';
        $replace = array();
        $matches = array();

        if ((bool) preg_match_all($pattern, $this->templateContent, $matches)) {

            foreach ($matches[0] as $index => $replaceString) {
                $base       = trim($matches[1][$index] . '\\', ' ');
                $tmp        = explode(',', $matches[2][$index]);
                $namespaces = array();
                foreach ($tmp as $class) {
                    $namespaces[] = $base . trim($class);
                }

                $replace[$replaceString] = '<?php use ' . implode(', ', $namespaces) . '; ?' . '>';
            }

            $this->templateContent = trim(str_replace(array_keys($replace), $replace, $this->templateContent));
        }

        return $this->templateContent;
    }

}