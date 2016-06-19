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
class PatternEcho extends PatternAbstract
{
    /**
     * Search & replace current defined pattern for template.
     * Example of template:
     *
     * <code>
     * # In Template:
     * #
     * # ({@i$intVar}}
     * # ({@f$floatVar}}
     * # ({@s$stringVar}}
     * # ({@e$stringVar}}
     * # ({@$stringVar}}
     * #
     * # In Compiled template:
     * <=(int) $intVar?>
     * <=(float) $intVar?>
     * <=(string) $intVar?>
     * <=htmle($intVar)?>
     * <=$intVar?>
     * </code>
     *
     * @return string Compiled template
     */
    public function render()
    {
        //~ Search echo
        $pattern = '`{{@((i|f|s|e)[|])?(.+?)}}`is';
        $replace = array();
        $matches = array();

        if ((bool) preg_match_all($pattern, $this->templateContent, $matches)) {

            foreach ($matches[0] as $index => $replaceString) {

                $cast    = $matches[2][$index];
                $content = $matches[3][$index];

                switch ($cast) {
                    case 'i':
                        $cast = '(int) ';
                        break;
                    case 'f':
                        $cast = '(float) ';
                        break;
                    case 's':
                        $cast = '(string) ';
                        break;
                    case 'e':
                        $cast = 'htmle(';
                        $content .= ')';
                        break;
                    default:
                        $cast = '';
                }

                $replace[$replaceString] = '<?=' . $cast . $content . '?>';
            }

            $this->templateContent = trim(str_replace(array_keys($replace), $replace, $this->templateContent));
        }

        return $this->templateContent;
    }
}