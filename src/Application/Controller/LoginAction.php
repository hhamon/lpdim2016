<?php

namespace Application\Controller;

use Application\User\UserLoginForm;
use Application\User\UserLoginValidator;
use Framework\AbstractAction;
use Framework\Form\FormError;
use Framework\Form\FormInterface;
use Framework\Http\Request;
use Framework\Security\Authentication\BadCredentialsException;

class LoginAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        $form = new UserLoginForm(new UserLoginValidator());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->authenticate($form, $request);
        }

        return $this->render('login.html.twig', ['form' => $form->createView()]);
    }

    private function authenticate(FormInterface $form, Request $request)
    {
        try {
            $authenticator = $this->getService('security.authenticator');
            $authenticator->authenticate($request);

            return $this->redirectToRoute('dashboard');
        } catch (BadCredentialsException $e) {
            $form->addError(new FormError('username', 'Les identifiants sont incorrects.'));
        } catch (\Exception $e) {
            $form->addError(new FormError('username', 'Une erreur interne a empêché l\'authentification.'));
        }

        return $this->render('login.html.twig', ['form' => $form->createView()]);
    }
}
