<?php

namespace Muse\Entity;

use Simplex\Entity\Paginator;

use Symfony\Component\Finder\Finder;

class Gallery implements \Iterator {
    protected $collection = array();
    protected $protections = array();

    protected $pointer;

    protected $album;
    protected $page;
    protected $nbPerPage;


    use Paginator;


    public function __construct(array $protections, Album $album, $page, $nbPerPage) {
        $this->setProtections($protections);

        $this->album       = $album;
        $this->page        = $page;
        $this->nbPerPage   = $nbPerPage;

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

        foreach($this->protections as $protection) {
            if(strpos($protection, $item->getPath()) === 0) {
                return $this;
            }
        }

        $this->collection[] = $item;

        return $this;
    }

    public function getCollection() {
        return $this->collection;
    }

    protected function setProtections(array $protections) {
        $this->protections = array();

        foreach($protections as $protection) {
            $this->protections[$protection->getPath()] = $protection->getProtector()->getId();
        }

        return $this;
    }

    public function hasProtection($path = null) {
        if($path === null) {
            $path = $this->album->getRelativePath();
        }

        return isset($this->protections[$path]);
    }
}