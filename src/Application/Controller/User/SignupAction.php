<?php

namespace Application\Controller\User;

use Application\User\User;
use Application\User\UserSignupForm;
use Application\User\UserSignupValidator;
use Framework\AbstractAction;
use Framework\Http\Request;
use Framework\Security\Password\RandomNumberGenerator;

class SignupAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $form = new UserSignupForm(new UserSignupValidator());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->getService('security.password_encoder');
            $user = User::fromArray($form->getData(function (array $data) use ($encoder) {
                $data['salt'] = RandomNumberGenerator::randomBytes();
                $data['password'] = $encoder->encodePassword($data['password'], $data['salt']);
                unset($data['confirmation']);

                return $data;
            }));
            $user->addPermission('USER');
            $this->getService('repository.user')->save($user);

            return $this->redirectToRoute('user_signup_success');
        }

        return $this->render('user/signup.twig', ['form' => $form->createView()]);
    }
}
