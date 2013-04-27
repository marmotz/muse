<?php

namespace Mattlab\MuseBundle\Entity;

abstract class Item {
    const TYPE_ALBUM = 'album';
    const TYPE_PHOTO = 'photo';

    protected $galleryRootPath;
    protected $relativePath;


    public function __construct($galleryRootPath, $relativePath)
    {
        $this
            ->setGalleryRootPath($galleryRootPath)
            ->setRelativePath($relativePath)
        ;
    }


    public function setGalleryRootPath($galleryRootPath)
    {
        $this->galleryRootPath = $galleryRootPath;

        return $this;
    }

    public function getGalleryRootPath()
    {
        return $this->galleryRootPath;
    }


    public function setRelativePath($relativePath)
    {
        $this->relativePath = trim($relativePath, DIRECTORY_SEPARATOR);

        return $this;
    }

    public function getRelativePath() {
        return $this->relativePath;
    }


    public function getBasename() {
        return basename($this->relativePath);
    }


    public function getName() {
        return $this->getBasename();
    }


    public function getPath() {
        return $this->galleryRootPath . $this->relativePath;
    }


    public function isAlbum() {
        return $this->getType() === self::TYPE_ALBUM;
    }

    public function isPhoto() {
        return $this->getType() === self::TYPE_PHOTO;
    }

    abstract public function getType();


    public function getParentPaths() {
        $paths = array();
        $path  = dirname($this->getRelativePath());

        while($path !== '.') {
            $paths[] = $path;

            $path = dirname($path);
        }

        return $paths;
    }
}