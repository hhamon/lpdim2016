<?php

namespace Application\Controller;

use Framework\AbstractAction;

class HomePageAction extends AbstractAction
{
    public function __invoke()
    {
        return $this->render('home.twig');
    }
}