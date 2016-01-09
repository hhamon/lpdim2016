<?php

namespace Tests\Framework\Renderer;

use Framework\Templating\AbstractRenderer;

class AbstractRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDirectory()
    {
        $this->getMockForAbstractClass(AbstractRenderer::class, ['/foooooooo']);
    }
}
