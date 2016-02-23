<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 22/02/2016
 * Time: 21:36
 */

namespace Application\Controller\Login;


use Application\Html\HtmlBuilder;
use Framework\AbstractAction;
use Framework\Http\RequestInterface;

class SignUpAction extends AbstractAction
{
    /**
     * Sign up a guest
     * @param RequestInterface $request
     * @return \Framework\Http\RedirectResponse
     */
    public function __invoke(RequestInterface $request)
    {
        $html = new HtmlBuilder();
        $session = $this->getService('session');
        if($request->getMethod() == "POST"){
            $username = $request->getRequestParameter('username',false);
            $password = $request->getRequestParameter('password',false);
            $args['session'] = $session;
            $args['html'] = $html;
            $args['session'] = $session;
            //verifying params
            if(!$username && !$password){
                $session->store('error','Aucun champ n\'a été rempli');
                return $this->render('login/signup.twig',$args);
            }
            if(!$username){
                $session->store('error.username','Le nom d\'utilisateur est vide');
                return $this->render('login/signup.twig',$args);
            }
            if(!$password){
                $session->store('error.password','Le mot de passe est vide');
                $args['username'] = $username;
                return $this->render('login/signup.twig',$args);
            }
            //verifying account
            $repository = $this->getService('repository.guest');
            if($auth = $repository->auth([
                'username' => $username,
                'password' => hash('sha512',$password.$this->getParameter('security.salt'))
            ])){
                //store in the session
                $session->store('auth',$auth);
                return $this->redirect('/index.php/blog',301);
            } else {
                //connection failed
                $session->store('error','La connection à échouée');
                return $this->render('login/signup.twig',compact('html','session'));
            }
        }
        //show the form
        return $this->render('login/signup.twig',compact('html','session'));
    }
}