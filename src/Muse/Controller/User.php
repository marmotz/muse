<?php

namespace Muse\Controller;

use Simplex\Controller\EntityManagerInjectable;
use Simplex\Controller\EntityManagerInjector;
use Simplex\Controller\UrlGeneratorInjectable;
use Simplex\Controller\UrlGeneratorInjector;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class User implements EntityManagerInjectable, UrlGeneratorInjectable {
    use EntityManagerInjector;
    use UrlGeneratorInjector;

    public function signInAction() {
        return array();
    }

    public function loginAction(Request $request) {
        $email    = $request->get('email');
        $password = $request->get('password');

        $user = $this
            ->getEntityManager()
            ->getRepository('Muse\Entity\User')
            ->findOneByEmail($email)
        ;

        if($user && $user->isPasswordValid($password)) {
            $request->getSession()->set('user', $user);

            return new RedirectResponse(
                $this->getUrlGenerator()->generate('Home')
            );
        }
        else {
            $request->getSession()->getFlashBag()->add(
                'error',
                'form.login.unknownuser'
            );

            return new RedirectResponse(
                $this->getUrlGenerator()->generate('UserSignIn')
            );
        }
    }

    public function signOutAction(Request $request) {
        $request->getSession()->remove('user');

        return new RedirectResponse(
            $this->getUrlGenerator()->generate('Home')
        );
    }
}