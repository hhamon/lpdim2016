<?php

namespace Application\Controller\User;

use Framework\AbstractAction;

class SignupSuccessAction extends AbstractAction
{
    public function __invoke()
    {
        return $this->render('user/success.twig');
    }
}
