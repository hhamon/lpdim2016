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

class SignInAction extends AbstractAction
{
    /**
     * Could sign in guest
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
            $password_confirm = $request->getRequestParameter('password_confirm',false);
            $args['session'] = $session;
            $args['html'] = $html;
            $args['session'] = $session;
            //verifying parameters
            if(!$username && !$password && !$password_confirm){
                $session->store('error','Aucun champ n\'a été rempli');
                return $this->render('login/signin.twig',$args);
            }
            if(!$username){
                $session->store('error.username','Le nom d\'utilisateur est vide');
                return $this->render('login/signin.twig',$args);
            }
            if(!$password){
                $session->store('error.password','Le mot de passe est vide');
                $args['username'] = $username;
                return $this->render('login/signin.twig',$args);
            }
            if($password !== $password_confirm){
                $session->store('error.password','Le mot de passe et sa confirmation diffèrent.');
                $args['username'] = $username;
                return $this->render('login/signin.twig',$args);
            }
            $repository = $this->getService('repository.guest');
            //hash the password
            $hashed_password = hash('sha512',$password.$this->getParameter('security.salt'));
            //create a new guest
            if($id = $repository->create([
                'username' => $username,
                'password' => $hashed_password,
            ])){
                //admin guest to session
                $session->store('auth',[
                    'id'       => $id,
                    'username' => $username,
                    'password' => $hashed_password,
                ]);
                //redirect to blog
                return $this->redirect(
                    "/index.php/blog"
                    ,301
                );
            } else {
                //guest failed
                $session->store('error','L\'enregistrement à échoué.');
                return $this->redirect(
                    "/index.php/se-connecter"
                    ,301
                );
            }
        }
        //show the signin form
        return $this->render('login/signin.twig',compact('html','session'));
    }
}