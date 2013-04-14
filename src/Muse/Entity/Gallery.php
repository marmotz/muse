<?php

namespace Muse\Entity;

use Iterator;
use Simplex\Entity\Paginator;
use Symfony\Component\Finder\Finder;

class Gallery implements Iterator {
    protected $collection = array();
    protected $protections = array();

    protected $pointer;

    protected $album;
    protected $page;
    protected $nbPerPage;
    protected $user;


    use Paginator;


    public function __construct(array $protections, Album $album, $page, $nbPerPage, User $user = null) {
        $this->setProtections($protections);

        $this->album     = $album;
        $this->page      = $page;
        $this->nbPerPage = $nbPerPage;
        $this->user      = $user;

        $finder = new Finder();
        $finder
            ->directories()
            ->in($album->getPath())
            ->sortByName()
            ->depth(0)
        ;

        $this->addItems($finder);

        $finder
            ->files()
            ->name('/\.(jpg|jpeg|png)$/i')
        ;

        $this
            ->addItems($finder)
            ->paginate($page, $nbPerPage)
        ;
    }

    public function getPath() {
        return $this->album->getRelativePath();
    }

    public function getParentPath() {
        return dirname($this->getPath());
    }

    public function getBreadCrumb() {
        return explode('/', $this->getPath());
    }

    public function isRoot() {
        return $this->album->getRootPath() === $this->album->getPath();
    }


    public function current() {
        return $this->collection[$this->pointer];
    }

    public function key() {
        return $this->pointer;
    }

    public function next() {
        $this->pointer++;
    }

    public function rewind() {
        $this->pointer = 0;
    }

    public function valid() {
        return isset($this->collection[$this->pointer]);
    }

    protected function addItems($items) {
        foreach($items as $item) {
            $this->addItem($item, false);
        }

        return $this;
    }

    protected function addItem($item) {
        if($item instanceof \SplFileInfo) {
            $item = $item->isDir() ? new Album($item) : new Photo($item);
        }
        else if(is_string($item)) {
            if(file_exists($item)) {
                $item = is_dir($item) ? new Album($item) : new Photo($item);
            }
            else {
                throw new \RuntimeException($item . ' is not a valid directory or file path.');
            }
        }
        else {
            throw new \RuntimeException('Invalid item');
        }

        $this->collection[] = $item;

        return $this;
    }

    protected function setProtections(array $protections) {
        $this->protections = array();

        foreach($protections as $protection) {
            $this->protections[$protection->getPath()] = $protection;
        }

        return $this;
    }

    public function hasProtection($path = null) {
        if($path === null) {
            $path = $this->album->getRelativePath();
        }

        return isset($this->protections[$path]);
    }

    public function hasParentProtection() {
        foreach(Protection::getParentPaths($this->album->getRelativePath()) as $key => $path) {
            if($this->hasProtection($path)) {
                return true;
            }
        }

        return false;
    }

    public function getCollection() {
        $gallery = $this;

        return array_filter(
            $this->collection,
            function($item) use($gallery) {
                return $gallery->canAccess(
                    $item->getRelativePath()
                );
            }
        );
    }

    public function canAccess($path) {
        return
               !$this->hasProtection($path)
            || (
                   $this->user !== null
                && $this->user->getId() === $this->protections[$path]->getProtector()->getId()
            )
        ;
    }
}