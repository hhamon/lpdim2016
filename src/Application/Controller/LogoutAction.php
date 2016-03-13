<?php

namespace Application\Controller;

use Framework\AbstractAction;
use Framework\Http\Request;

class LogoutAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $this->getService('security.logout_authenticator')->authenticate($request);

        return $this->redirectToRoute('login');
    }
}
