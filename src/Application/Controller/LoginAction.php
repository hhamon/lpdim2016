<?php

namespace Application\Controller;

use Framework\AbstractAction;

class LoginAction extends AbstractAction
{
    public function __invoke()
    {
        return $this->render('login.html.twig');
    }
}
