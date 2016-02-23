<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/02/2016
 * Time: 23:17
 */

namespace Application\Controller\Login;


use Framework\AbstractAction;
use Framework\Http\RequestInterface;

class HomeAction extends AbstractAction
{
    public function __invoke(RequestInterface $req)
    {
        return $this->render('hello.twig',['name'=>'test']);
    }
}