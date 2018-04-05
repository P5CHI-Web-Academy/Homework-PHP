<?php

use PHPUnit\Framework\TestCase;

final class TemplaterTest extends TestCase{
    protected static $templates;

    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        if(!defined('TEMPLATES_PATH')){
            define('TEMPLATES_PATH', __DIR__.'/../views/');  // Test path
        }

        self::$templates = array(
            'template1' => '',
            'template2' => '{{var}}',
            'template3' => '{{var}} {{not_var} }',
            'template4' => '{{varvar}}',
            'template5' => '{{va\nr}}{{v\tar}}',
            'template6' => '<br/><br/>{{var}}<br/>'
        );

        foreach(self::$templates as $t_name => $t_content){
            $t_path = TEMPLATES_PATH.'/'.$t_name.'.html';
            file_put_contents($t_path, $t_content);
        }
    }

    /**
     * @dataProvider templateContentProvider
     */
    public function testTemplaterReturnsValidProcessedText($template_name, $variables, $expected){
        $mock = $this->getMockBuilder(\Service\Templater::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $this->assertSame($expected, $mock->process_template($template_name, $variables));
    }

    public function templateContentProvider(){
        $data = array(
            array('template1', array(), ''),
            array('template2', array('var'=>'text'), 'text'),
            array('template3', array('var'=>'text1', 'not_var'=>'text2'), 'text1 {{not_var} }'),
            array('template4', array('var'=>'text'), ''),
            array('template5', array('var'=>'text'), '{{va\nr}}{{v\tar}}'),
            array('template6', array('var'=>'text'), "<br/><br/>text<br/>")
        );

        return $data;
    }

    public static function tearDownAfterClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        foreach(self::$templates as $t_name => $t_content){
            $t_path = TEMPLATES_PATH.'/'.$t_name.'.html';
            unlink($t_path);
        }
    }
}