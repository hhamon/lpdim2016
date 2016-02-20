<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 20/02/2016
 * Time: 16:29
 */

namespace Tests\Framework\Html;


use Michelf\Markdown;

class MarkdownTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformMarkdownToHtml()
    {
        $markdown = "**test**";
        $string = "<p><strong>test</strong></p>";
        $this->assertEquals($string,trim(Markdown::defaultTransform($markdown)));
    }

    public function testTransformContentWithoutMarkdown()
    {
        $content = "test";
        $this->assertEquals("<p>".$content."</p>",trim(Markdown::defaultTransform($content)));
    }

    public function testAddSlashes()
    {
        $md = "**test**";
        $string = addslashes("<p><strong>test</strong></p>");
        $this->assertEquals($string,trim(Markdown::defaultTransform($md)));
    }

    public function testRemoveSlahes()
    {
        $md = trim(addslashes(Markdown::defaultTransform("**test**")));
        $this->assertEquals(stripslashes($md),"<p><strong>test</strong></p>");
    }
}