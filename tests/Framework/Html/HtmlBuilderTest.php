<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 17/02/2016
 * Time: 23:08
 */

namespace Tests\Framework\Html;

use Application\Html\HtmlBuilder;

class HtmlBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInput()
    {
        $builder = new HtmlBuilder();
        $this->assertEquals(
            $builder->input('text','name','test'),
            '<input type="text" name="name" id="name" value="test" class="form-control"/>'
        );
    }
    public function testCreateTextarea()
    {
        $builder = new HtmlBuilder();
        $this->assertEquals(
            $builder->textarea('name','test'),
            '<textarea name="name" id="name" class="form-control">test</textarea>'
        );
    }
    public function testCreateForm()
    {
        $builder = new HtmlBuilder();
        $this->assertEquals(
            $builder->form('post','test.php'),
            '<form action="test.php" method="post" class="form">'
        );
    }

    public function  testTextareaLabel()
    {
        $builder = new HtmlBuilder();
        $string = '<div class="form-group"><label for="name" class="control-label">Label</label>';
        $string .= '<textarea name="name" id="name" class="form-control">value</textarea></div>';
        $this->assertEquals(
            $string,
            $builder->textareaLabel('Label','name','value')
        );
    }

    public function stripSlashes($text)
    {
        return stripslashes($text);
    }

    public function  testInputLabel()
    {
        $builder = new HtmlBuilder();
        $string = '<div class="form-group"><label for="name" class="control-label">Label</label>';
        $string .= '<input type="text" name="name" id="name" value="value" class="form-control"/></div>';
        $this->assertEquals(
            $string,
            $builder->inputLabel('Label','text','name','value')
        );
    }
}