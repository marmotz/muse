<?php

namespace Muse\Entity;

abstract class Item {
    const TYPE_ALBUM = 'album';
    const TYPE_PHOTO = 'photo';

    protected $path;    protected $name;

    public function __construct(\SplFileInfo $file) {
        $this->name = $file->getBasename();
    }

    public function getName() {
        return $this->name;
    }


    public function isAlbum() {
        return $this->getType() === self::TYPE_ALBUM;
    }

    public function isPhoto() {
        return $this->getType() === self::TYPE_PHOTO;
    }

    abstract public function getType();
}