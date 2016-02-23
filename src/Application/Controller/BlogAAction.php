<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 23/02/2016
 * Time: 23:40
 */

namespace Application;


use Framework\AbstractAction;

class BlogAAction extends AbstractAction
{
    public function __invoke()
    {
        return $this->render("hello.twig", ['name'=>'test']);

    }
}