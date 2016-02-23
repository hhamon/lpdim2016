<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 23/02/2016
 * Time: 23:39
 */

namespace Tests\Framework;

use Application\BlogAAction;
use Application\Controller\Blog\BlogAction;
use Framework\ControllerFactory;

class ControllerFactoryShortTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateControllerWithOneFolderName()
    {
        $factory = new ControllerFactory();

        $this->assertInstanceOf(BlogAAction::class, $factory->createController([ '_controller' => 'App:BlogA' ]));
    }

    public function testCreateControllerWithTwoFolderName()
    {
        $factory = new ControllerFactory();

        $this->assertInstanceOf(BlogAction::class, $factory->createController([ '_controller' => 'App:Blog:Blog' ]));
    }
}
