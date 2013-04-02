<?php

namespace Muse\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Muse\Entity\ItemCollection;

class Album {
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

    public function displayAction($album, $page, $nbPerPage) {
        $fullAlbumPath = self::getItemFullPath($album);

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

        return array(
            'isRoot'    => self::getGalleryRootPath() === $fullAlbumPath,
            'albumPath' => $album,
            'items'     => $items,
            'page'      => $page,
            'nbPerPage' => $nbPerPage,
            'nbPages'   => $items->getNbPages(),
        );
    }
}