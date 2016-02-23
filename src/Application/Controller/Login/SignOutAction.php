<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/02/2016
 * Time: 00:43
 */

namespace Application\Controller\Login;


use Framework\AbstractAction;
use Framework\Http\RequestInterface;
use Framework\Http\Response;

class SignOutAction extends AbstractAction
{
    /**
     * sign out any guest
     * @param RequestInterface $request
     * @return \Framework\Http\RedirectResponse
     */
    public function __invoke(RequestInterface $request)
    {
        //remove index in the session and redirect to connection
        $session = $this->getService('session');
        $session->fetchAndUnset('auth');
        return $this->redirect('/index.php/login/se-connecter',Response::HTTP_PERMANENTLY_REDIRECT);
    }
}