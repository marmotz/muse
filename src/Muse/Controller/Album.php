<?php

namespace Muse\Controller;

use Muse\Entity;

use Simplex\Controller\EntityManagerInjectable;
use Simplex\Controller\EntityManagerInjector;
use Simplex\Controller\UrlGeneratorInjectable;
use Simplex\Controller\UrlGeneratorInjector;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Album implements EntityManagerInjectable, UrlGeneratorInjectable {
    use EntityManagerInjector;
    use UrlGeneratorInjector;


    public function displayAction(Request $request, $albumPath, $page, $nbPerPage) {
        $album = new Entity\Album($albumPath);

        $session = $request->getSession();

        $gallery = new Entity\Gallery(
            $this->getEntityManager()->getRepository('Muse\Entity\Protection')->findByAlbumPath(
                $album->getRelativePath()
            ),
            $album,
            $page,
            $nbPerPage,
            $session->has('user') ? $session->get('user') : null
        );

        $session->set('lastAlbumPath', $album->getPath());
        $session->set('lastPage',      $page);
        $session->set('lastNbPerPage', $nbPerPage);

        return array(
            'gallery'   => $gallery,
            'page'      => $page,
            'nbPerPage' => $nbPerPage,
        );
    }

    public function protectAction($albumPath) {
        return array(
            'albumPath' => $albumPath,
        );
    }

    public function cryptAction(Request $request, $albumPath) {
        $password = $request->get('password');

        $em = $this->getEntityManager();

        $user = $em
            ->getRepository('Muse\Entity\User')
            ->findOneById(
                $request->getSession()->get('user')->getId()
            )
        ;

        if($user && $user->isPasswordValid($password)) {
            $protection = new Entity\Protection;
            $protection
                ->setPath($albumPath)
                ->setProtector($user)
            ;

            $em->persist($protection);
            $em->flush();

            return new RedirectResponse(
                $this->getUrlGenerator()->generate(
                    'AlbumDisplay',
                    array(
                        'albumPath' => $albumPath,
                        'page'      => $request->getSession()->get('lastPage', 1),
                        'nbPerPage' => $request->getSession()->get('lastNbPerPage', 50),
                    )
                )
            );
        }
        else {
            $request->getSession()->getFlashBag()->add(
                'error',
                'form.protect.wrongpassword'
            );

            return new RedirectResponse(
                $this->getUrlGenerator()->generate(
                    'AlbumProtect',
                    array(
                        'albumPath' => $albumPath,
                    )
                )
            );
        }
    }

    public function unprotectAction($albumPath) {
        return array(
            'albumPath' => $albumPath,
        );
    }

    public function decryptAction(Request $request, $albumPath) {
        $password = $request->get('password');

        $em = $this->getEntityManager();

        $user = $em
            ->getRepository('Muse\Entity\User')
            ->findOneById(
                $request->getSession()->get('user')->getId()
            )
        ;

        if($user && $user->isPasswordValid($password)) {
            $protection = $em
                ->getRepository('Muse\Entity\Protection')
                ->findOneByPath(
                    $albumPath
                )
            ;

            $em->remove($protection);
            $em->flush();

            return new RedirectResponse(
                $this->getUrlGenerator()->generate(
                    'AlbumDisplay',
                    array(
                        'albumPath' => $albumPath,
                        'page'      => $request->getSession()->get('lastPage', 1),
                        'nbPerPage' => $request->getSession()->get('lastNbPerPage', 50),
                    )
                )
            );
        }
        else {
            $request->getSession()->getFlashBag()->add(
                'error',
                'form.unprotect.wrongpassword'
            );

            return new RedirectResponse(
                $this->getUrlGenerator()->generate(
                    'AlbumUnprotect',
                    array(
                        'albumPath' => $albumPath,
                    )
                )
            );
        }
    }
}