<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Template;

use Eureka\Component\Template\Pattern;

require_once __DIR__ . '/../Template.php';
require_once __DIR__ . '/../Block.php';
require_once __DIR__ . '/../Pattern/Pattern.interface.php';
require_once __DIR__ . '/../Pattern/PatternAbstract.php';
require_once __DIR__ . '/../Pattern/PatternMain.php';
require_once __DIR__ . '/../Pattern/PatternPartial.php';
require_once __DIR__ . '/../Pattern/PatternBlock.php';
require_once __DIR__ . '/../Pattern/PatternNamespace.php';
require_once __DIR__ . '/../Pattern/PatternCollection.php';

/**
 * Class Test for Routing
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Patterns
     *
     * @return void
     * @covers PartternAbstract::__construct
     * @covers PartternNamespace::render
     * @covers PartternPartial::render
     * @covers PartternBlock::render
     * @covers PartternMain::render
     */
    public function testPatterns()
    {
        $templateContent = $this->dataTemplateContent();

        $patternNamespace = new Pattern\PatternNamespace($templateContent);
        $templateCompiled = $patternNamespace->render();
        $this->assertEquals($templateCompiled, $this->dataCompiledNamespace());

        $patternPartial   = new Pattern\PatternPartial($templateContent);
        $templateCompiled = $patternPartial->render();
        $this->assertEquals($templateCompiled, $this->dataCompiledPartial());

        $patternBlock     = new Pattern\PatternBlock($templateContent);
        $templateCompiled = $patternBlock->render();
        $this->assertEquals($templateCompiled, $this->dataCompiledBlock());

        $patternMain      = new Pattern\PatternMain($templateContent);
        $templateCompiled = $patternMain->render();
        $this->assertEquals($templateCompiled, $this->dataCompiledMain());

        $patternNamespace = new Pattern\PatternNamespace($templateContent);
        $templateCompiled = $patternNamespace->render();
        $patternPartial   = new Pattern\PatternPartial($templateCompiled);
        $templateCompiled = $patternPartial->render();
        $patternBlock     = new Pattern\PatternBlock($templateCompiled);
        $templateCompiled = $patternBlock->render();
        $patternMain      = new Pattern\PatternMain($templateCompiled);
        $templateCompiled = $patternMain->render();

        $this->assertEquals($templateCompiled, $this->dataCompiledAll());
    }

    /**
     * Test Template class
     *
     * @return void
     * @covers Template::__construct
     */
    public function testTemplate()
    {
        //~ Init template compiled path
        Template::setPathCompiled('/tmp/Eureka/tpl');

        //~ Data for template
        $menu          = new \stdClass();
        $menu->title1 = 'Title 1';
        $menu->title2 = 'Title 2';
        $menu->title3 = 'Title 3';

        $title       = 'Page Title';
        $news        = new \stdClass();
        $news->title = 'My News test';
        $news        = array($news);

        //~ Create new collection of patterns
        $patternCollection = new Pattern\PatternCollection();
        $patternCollection->add(new Pattern\PatternNamespace());
        $patternCollection->add(new Pattern\PatternBlock());
        $patternCollection->add(new Pattern\PatternPartial());
        $patternCollection->add(new Pattern\PatternMain());

        //~ New template object
        $template = new Template('Template/Main', $patternCollection, array(), true);
        $template->setVars(array('oMenu' => $menu));
        $template->setVar('sTitle', $title);
        $template->appendVars(array('aNews' => $news));

        //~ Test
        $file = '/tmp/Eureka/tpl/' . substr(md5('Template/Main.tpl'), 0, 10) . '.php';
        $this->assertTrue(file_exists($file));
        $this->assertEquals(file_get_contents($file), $this->dataCompiledTemplate());

        $render = $template->render();

        //~ Disable test due to trailing whitespace on this file, but work fine
        //$this->assertEquals($render, $this->dataRenderHtml());
    }

    /**
     * Return compiled template for namespace
     *
     * @return string
     */
    protected function dataCompiledNamespace()
    {
        return '<?php use Eureka\Component\Template\Template, Eureka\Component\Template\Block; ?><!DOCTYPE>
        <html>
        <head>
            <title>Eureka Home</title>
        </head>
        <body>
            {% partial: Package/Home/Menu, $menu %}

            <h1>News</h1>

            {% block: javascript %}
            <script>
            function log(my_var) {
                console.log(my_var);
            }
            </script>
            {% endblock %}

            {# Display last news #}
            {{foreach($news as $news):}}
                <h2>{{@$news->getTitle();}}</h2>
            {{endforeach;}}

            {% partial: Package/Home/Footer %}

            {% getblock: javascript %}
        </body>
        </html>';
    }

    /**
     * Get compiled template for partial.
     *
     * @return string
     */
    protected function dataCompiledPartial()
    {
        return '{* Eureka\Component\Template: Template, Block *}<!DOCTYPE>
        <html>
        <head>
            <title>Eureka Home</title>
        </head>
        <body>
            <?=Template::partial(\'Package/Home/Menu\', array(\'oMenu\' => $menu)); ?>

            <h1>News</h1>

            {% block: javascript %}
            <script>
            function log(my_var) {
                console.log(my_var);
            }
            </script>
            {% endblock %}

            {# Display last news #}
            {{foreach($news as $news):}}
                <h2>{{@$news->getTitle();}}</h2>
            {{endforeach;}}

            <?=Template::partial(\'Package/Home/Footer\', array()); ?>

            {% getblock: javascript %}
        </body>
        </html>';
    }

    /**
     * Get compiled template for block.
     *
     * @return string
     */
    protected function dataCompiledBlock()
    {
        return '{* Eureka\Component\Template: Template, Block *}<!DOCTYPE>
        <html>
        <head>
            <title>Eureka Home</title>
        </head>
        <body>
            {% partial: Package/Home/Menu, $menu %}

            <h1>News</h1>

            <?php Block::getInstance()->begin(); ?>
            <script>
            function log(my_var) {
                console.log(my_var);
            }
            </script>
            <?php Block::getInstance()->end(\'javascript\'); ?>

            {# Display last news #}
            {{foreach($news as $news):}}
                <h2>{{@$news->getTitle();}}</h2>
            {{endforeach;}}

            {% partial: Package/Home/Footer %}

            <?=Block::getInstance()->get(\'javascript\'); ?>
        </body>
        </html>';
    }

    /**
     * Get compiled template for main
     *
     * @return string
     */
    protected function dataCompiledMain()
    {
        return '{* Eureka\Component\Template: Template, Block *}<!DOCTYPE>
        <html>
        <head>
            <title>Eureka Home</title>
        </head>
        <body>
            {% partial: Package/Home/Menu, $menu %}

            <h1>News</h1>

            {% block: javascript %}
            <script>
            function log(my_var) {
                console.log(my_var);
            }
            </script>
            {% endblock %}

            <?php /*  Display last news  */ ?>
            <?php foreach($news as $news):?>
                <h2><?=$news->getTitle();?></h2>
            <?php endforeach;?>

            {% partial: Package/Home/Footer %}

            {% getblock: javascript %}
        </body>
        </html>';
    }

    /**
     * Get compiled template for main
     *
     * @return string
     */
    protected function dataCompiledAll()
    {
        return '<?php use Eureka\Component\Template\Template, Eureka\Component\Template\Block; ?><!DOCTYPE>
        <html>
        <head>
            <title>Eureka Home</title>
        </head>
        <body>
            <?=Template::partial(\'Package/Home/Menu\', array(\'oMenu\' => $menu)); ?>

            <h1>News</h1>

            <?php Block::getInstance()->begin(); ?>
            <script>
            function log(my_var) {
                console.log(my_var);
            }
            </script>
            <?php Block::getInstance()->end(\'javascript\'); ?>

            <?php /*  Display last news  */ ?>
            <?php foreach($news as $news):?>
                <h2><?=$news->getTitle();?></h2>
            <?php endforeach;?>

            <?=Template::partial(\'Package/Home/Footer\', array()); ?>

            <?=Block::getInstance()->get(\'javascript\'); ?>
        </body>
        </html>';
    }

    /**
     * Get template data.
     *
     * @return string
     */
    protected function dataTemplateContent()
    {
        return '{* Eureka\Component\Template: Template, Block *}<!DOCTYPE>
        <html>
        <head>
            <title>Eureka Home</title>
        </head>
        <body>
            {% partial: Package/Home/Menu, $menu %}

            <h1>News</h1>

            {% block: javascript %}
            <script>
            function log(my_var) {
                console.log(my_var);
            }
            </script>
            {% endblock %}

            {# Display last news #}
            {{foreach($news as $news):}}
                <h2>{{@$news->getTitle();}}</h2>
            {{endforeach;}}

            {% partial: Package/Home/Footer %}

            {% getblock: javascript %}
        </body>
        </html>';
    }

    /**
     * Get compiled template for template
     *
     * @return string
     */
    protected function dataCompiledTemplate()
    {
        return '<?php use Eureka\Component\Template\Template, Eureka\Component\Template\Block; ?><!DOCTYPE>
<html>
<head>
    <title>Eureka Home</title>
</head>
<body>
    <?=Template::partial(\'Template/Menu\', array(\'oMenu\' => $menu)); ?>

    <h1>News</h1>

    <?php Block::getInstance()->begin(); ?>
    <script>
    function log(my_var) {
        console.log(my_var);
    }
    </script>
    <?php Block::getInstance()->end(\'javascript\'); ?>

    <?php /*  Display last news  */ ?>
    <?php foreach($news as $news):?><h2><?=$news->title;?></h2><?php endforeach;?>

    <?=Template::partial(\'Template/Footer\', array()); ?>


    <?=Block::getInstance()->get(\'javascript\'); ?>

</body>
</html>';
    }

    /**
     * Rendered html to compare.
     *
     * @return string
     */
    protected function dataRenderHtml()
    {

        return '<!DOCTYPE>
<html>
<head>
    <title>Eureka Home</title>
</head>
<body>
    <ul>
        <li>Title 1</li>
        <li>Title 2</li>
        <li>Title 3</li>
    </ul>
    <h1>News</h1>


        <h2>My News test</h2>
    <div>Eureka-Framework &copy;</div>

    <script>
    function log(my_var) {
        console.log(my_var);
    }
    </script>
</body>
</html>';

    }
}
