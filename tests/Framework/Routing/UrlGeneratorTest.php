<?php

namespace tests\Framework\Routing;

use Framework\Routing\Loader\LazyFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\RequestContext;
use Framework\Routing\UrlGenerationException;
use Framework\Routing\UrlGenerator;
use Framework\Routing\UrlGeneratorInterface;

class UrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private function createGenerator()
    {
        return new UrlGenerator(new LazyFileLoader(__DIR__.'/Fixtures/routes.php', new PhpFileLoader()));
    }

    /**
     * @expectedException \Framework\Routing\UrlGenerationException
     */
    public function testGenerateUrlWhenRequestContextIsNotSet()
    {
        $this->createGenerator()->generate('foo');
    }

    public function testCannotGenerateUrlWhenParameterIsMissing()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'https', 1010));

        $this->setExpectedException(UrlGenerationException::class);
        $generator->generate('article');
    }

    public function testCannotGenerateUrlWhenParameterDoesNotMatchItsRequirement()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'https', 1010));

        $this->setExpectedException(UrlGenerationException::class);
        $generator->generate('article', [
            'year' => 'foo',
            'month' => 'bar',
            'day' => 'baz',
            'slug' => 'one-super-article',
        ]);
    }

    public function testGenerateUrlForInvalidRoute()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'https', 1010));

        $this->setExpectedException(UrlGenerationException::class);
        $generator->generate('foo_bar');
    }

    public function testGenerateAbsolutePathWithNonStandardPort()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'https', 1010));

        $this->assertSame('https://www.domain.tld:1010/home', $generator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('https://www.domain.tld:1010/login', $generator->generate('login', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('https://www.domain.tld:1010/login?foo=1&bar=2', $generator->generate('login', ['foo' => '1', 'bar' => '2'], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('https://www.domain.tld:1010/blog/2016/03/02/one-super-article.html', $generator->generate('article', ['year' => '2016', 'month' => '03', 'day' => '02', 'slug' => 'one-super-article'], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    public function testGenerateAbsolutePathWithoutScriptName()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'https', 443));

        $this->assertSame('https://www.domain.tld/home', $generator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('https://www.domain.tld/login', $generator->generate('login', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('https://www.domain.tld/login?foo=1&bar=2', $generator->generate('login', ['foo' => '1', 'bar' => '2'], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('https://www.domain.tld/blog/2016/03/02/one-super-article.html', $generator->generate('article', ['year' => '2016', 'month' => '03', 'day' => '02', 'slug' => 'one-super-article'], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    public function testGenerateAbsolutePathWithScriptName()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'http', 80, '/index.php'));

        $this->assertSame('http://www.domain.tld/index.php/home', $generator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('http://www.domain.tld/index.php/login', $generator->generate('login', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('http://www.domain.tld/index.php/login?foo=1&bar=2', $generator->generate('login', ['foo' => '1', 'bar' => '2'], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->assertSame('http://www.domain.tld/index.php/blog/2016/03/02/one-super-article.html', $generator->generate('article', ['year' => '2016', 'month' => '03', 'day' => '02', 'slug' => 'one-super-article'], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    public function testGenerateRelativePathWithoutScriptName()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld'));

        $this->assertSame('/home', $generator->generate('home'));
        $this->assertSame('/login', $generator->generate('login'));
        $this->assertSame('/login?foo=1&bar=2', $generator->generate('login', ['foo' => '1', 'bar' => '2']));
        $this->assertSame('/blog/2016/03/02/one-super-article.html', $generator->generate('article', ['year' => '2016', 'month' => '03', 'day' => '02', 'slug' => 'one-super-article']));
    }

    public function testGenerateRelativePathWithScriptName()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld', 'http', 80, '/index.php'));

        $this->assertSame('/index.php/home', $generator->generate('home'));
        $this->assertSame('/index.php/login', $generator->generate('login'));
        $this->assertSame('/index.php/login?foo=1&bar=2', $generator->generate('login', ['foo' => '1', 'bar' => '2']));
        $this->assertSame('/index.php/blog/2016/03/02/one-super-article.html', $generator->generate('article', ['year' => '2016', 'month' => '03', 'day' => '02', 'slug' => 'one-super-article']));
    }

    public function testGenerateRelativePathWithDefaultParameters()
    {
        $generator = $this->createGenerator();
        $generator->setRequestContext(new RequestContext('GET', '/', 'www.domain.tld'));

        $this->assertSame('/blog/all', $generator->generate('blog_category'));
        $this->assertSame('/blog/cooking', $generator->generate('blog_category', ['category' => 'cooking']));
        $this->assertSame('/blog/cooking?format=json', $generator->generate('blog_category', ['category' => 'cooking', 'format' => 'json']));
        $this->assertSame('/blog/php?format=json&page=2', $generator->generate('blog_category', ['category' => 'php', 'format' => 'json', 'page' => '2']));
    }
}
