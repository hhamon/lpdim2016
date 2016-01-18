<?php

namespace Tests\Framework\Session;

use Framework\Session\Driver\ArrayDriver;
use Framework\Session\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testSessionAutoStartMustBeDisabled()
    {
        $this->createSession([
            'session.auto_start' => 1,
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotFetchInvalidVariableKey()
    {
        $session = $this->createSession();
        $session->fetch(true, false);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotStoreInvalidVariableKey()
    {
        $session = $this->createSession();
        $session->store(true, false);
    }

    public function testCannotBeRestartedAfterBeingDestroyed()
    {
        $session = $this->createSession();
        $session->store('one', 1);
        $session->destroy();

        $this->setExpectedException('RuntimeException');
        $session->start();
    }

    public function testDestroySession()
    {
        $session = $this->createSession();
        $session->store('one', 1);
        $session->store('two', 2);

        $this->assertSame(1, $session->fetch('one'));
        $this->assertSame(2, $session->fetch('two'));

        $this->assertTrue($session->destroy());

        $this->setExpectedException('RuntimeException');
        $this->assertNull($session->fetch('one'));
    }

    public function testSaveSession()
    {
        $session = $this->createSession();
        $this->assertTrue($session->save());
    }

    public function testStoreAndRetrieveValue()
    {
        $session = $this->createSession();

        $this->assertTrue($session->start());
        $this->assertTrue($session->store('foo', 'bar'));
        $this->assertSame('bar', $session->fetch('foo'));
        $this->assertNull($session->fetch('bar'));
        $this->assertSame('baz', $session->fetch('bar', 'baz'));
    }

    public function testGetId()
    {
        $session = $this->createSession(['session.id' => 'someid']);

        $this->assertSame('someid', $session->getId());
    }

    private function createSession(array $options = [])
    {
        $options = array_merge(['session.name' => 'test'], $options);

        return new Session(new ArrayDriver(), $options, true);
    }
}
