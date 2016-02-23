<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 23/02/2016
 * Time: 23:42
 */

namespace Application\Controller\Blog;


class BlogAction
{
    public function __invoke()
    {
        return $this->render("hello.twig", ['name'=>'test']);

    }
}