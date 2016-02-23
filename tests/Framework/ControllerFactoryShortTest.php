<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/02/2016
 * Time: 23:29
 */

namespace Tests\Framework;


use Application\Controller\Login\HomeAction;
use Application\Controller\LoginAction;
use Framework\ControllerFactory;

class ControllerFactoryShortTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateControllerWithOneFolderName()
    {
        $factory = new ControllerFactory();

        $this->assertInstanceOf(LoginAction::class, $factory->createController([ '_controller' => 'App:Login' ]));
    }

    public function testCreateControllerWithTwoFolderName()
    {
        $factory = new ControllerFactory();

        $this->assertInstanceOf(HomeAction::class, $factory->createController([ '_controller' => 'App:Login:Home' ]));
    }
}