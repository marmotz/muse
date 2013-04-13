<?php

namespace Muse\Entity;

abstract class Item {
    const TYPE_ALBUM = 'album';
    const TYPE_PHOTO = 'photo';

    protected $path;
    protected $name;

    public function __construct($file) {
        if($file instanceof \SplFileInfo) {
            $this->name = $file->getBasename();
            $this->path = $file->getPathname();
        }
        else {
            if(!file_exists($file) && file_exists($this->getRootPath() . '/' . $file)) {
                $file = $this->getRootPath() . '/' . ltrim($file, '/');
            }

            $this->name = basename($file);
            $this->path = $file;
        }

        $this->name = rtrim($this->name, '/');
        $this->path = rtrim($this->path, '/');

        if(strpos($this->path, $this->getRootPath()) !== 0) {
            throw new \Exception('Fuck hacker ! (try to access to ' . $this->path . ')');
        }
    }

    public function getRootPath() {
        return realpath(__DIR__ . '/../../../gallery');
    }

    public function getName() {
        return $this->name;
    }

    public function getPath() {
        return $this->path;
    }


    public function isAlbum() {
        return $this->getType() === self::TYPE_ALBUM;
    }

    public function isPhoto() {
        return $this->getType() === self::TYPE_PHOTO;
    }

    abstract public function getType();

    public function getRelativePath() {
        return rtrim(substr($this->path, strlen($this->getRootPath()) + 1), '/');
    }
}