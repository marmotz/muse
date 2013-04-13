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
            $session = $request->getSession();
            $session->set('user', $user);

            return new RedirectResponse(
                $this->getUrlGenerator()->generate(
                    'AlbumDisplay',
                    array(
                        'albumPath' => $session->get('lastAlbumPath', ''),
                        'page'      => $session->get('lastPage',      1),
                        'nbPerPage' => $session->get('lastNbPerPage', 50),
                    )
                )
            );
        }
        else {
            $request->getSession()->getFlashBag()->add(
                'error',
                'form.signin.unknownuser'
            );

            return new RedirectResponse(
                $this->getUrlGenerator()->generate('UserSignIn')
            );
        }
    }

    public function signOutAction(Request $request) {
        $session = $request->getSession();

        $session->remove('user');

        return new RedirectResponse(
            $this->getUrlGenerator()->generate(
                'AlbumDisplay',
                array(
                    'albumPath' => $session->get('lastAlbumPath', ''),
                    'page'      => $session->get('lastPage',      1),
                    'nbPerPage' => $session->get('lastNbPerPage', 50),
                )
            )
        );
    }
}