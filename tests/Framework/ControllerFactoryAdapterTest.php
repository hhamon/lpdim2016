<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 23/02/2016
 * Time: 19:45
 */

namespace Tests\Framework;

use Framework\ControllerFactoryAdapter;
use Framework\ControllerFactoryInterface;


class ControllerFactoryAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testStub()
    {

        $controller = new ControllerFactoryAdapter(new MockControllerFactory());

        $a = $controller->createController(['_controller' => "App:Controller\\Blog\\ListPostsAction"]);
        $this->assertEquals($a, ["_controller"=>"Application\\Controller\\Blog\\ListPostsAction"]);

        $a = $controller->createController(['_controller' => "App:Blog\\ListPostsAction"]);
        $this->assertEquals($a, ["_controller"=>"Application\\Controller\\Blog\\ListPostsAction"]);

        $a = $controller->createController(['_controller' => "App:Blog:ListPostsAction"]);
        $this->assertEquals($a, ["_controller"=>"Application\\Controller\\Blog\\ListPostsAction"]);

        $a = $controller->createController(['_controller' => "App:Blog:ListPosts"]);
        $this->assertEquals($a, ["_controller"=>"Application\\Controller\\Blog\\ListPostsAction"]);

        $a = $controller->createController(['_controller' => "Application:Blog:ListPosts"]);
        $this->assertEquals($a, ["_controller"=>"Application\\Controller\\Blog\\ListPostsAction"]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testControllerNameIsNotDefined()
    {
        $factory = new ControllerFactoryAdapter(new MockControllerFactory());
        $factory->createController([ 'foo' => 'bar' ]);
    }
}

class MockControllerFactory implements  ControllerFactoryInterface{
    public function createController(array $params){
        return $params;
    }
}



