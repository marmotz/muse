<?php

namespace Muse\Controller;

use Muse\Entity\ItemCollection;
use Muse\Entity\Protection;

use Simplex\Controller\EntityManagerInjectable;
use Simplex\Controller\EntityManagerInjector;
use Simplex\Controller\UrlGeneratorInjectable;
use Simplex\Controller\UrlGeneratorInjector;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Album implements EntityManagerInjectable, UrlGeneratorInjectable {
    use EntityManagerInjector;
    use UrlGeneratorInjector;


    static public function getGalleryRootPath() {
        return realpath(__DIR__ . '/../../../gallery');
    }

    static public function getItemFullPath($item) {
        $galleryRootPath = self::getGalleryRootPath();
        $fullAlbumPath = realpath($galleryRootPath . '/' . $item);

        if(!$fullAlbumPath
        || substr($fullAlbumPath, 0, strlen($galleryRootPath)) != $galleryRootPath) {
            throw new \Exception('Fuck hacker ! (try to access to ' . $fullAlbumPath . ')');
        }

        return $fullAlbumPath;
    }

    static public function getItemRelativePath($item) {
        return str_replace(self::getGalleryRootPath(), '', self::getItemFullPath($item));
    }

    public function displayAction(Request $request, $albumPath, $page, $nbPerPage) {
        $fullAlbumPath = self::getItemFullPath($albumPath);

        $items = new ItemCollection;

        $finder = new Finder();
        $finder
            ->directories()
            ->in($fullAlbumPath)
            ->sortByName()
            ->depth(0)
        ;

        $items->addItems($finder);

        $finder
            ->files()
            ->name('/\.(jpg|jpeg|png)$/i')
        ;

        $items->addItems($finder);

        $items->paginate($page, $nbPerPage);

        $session = $request->getSession();
        $session->set('lastAlbum',     $albumPath);
        $session->set('lastPage',      $page);
        $session->set('lastNbPerPage', $nbPerPage);

        return array(
            'isRoot'     => self::getGalleryRootPath() === $fullAlbumPath,
            'albumPath'  => $albumPath,
            'items'      => $items,
            'page'       => $page,
            'nbPerPage'  => $nbPerPage,
            'nbPages'    => $items->getNbPages(),
            'protection' => $this->getEntityManager()->getRepository('Muse\Entity\Protection')->findOneByAlbumPath($albumPath),
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
            $protection = new Protection;
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