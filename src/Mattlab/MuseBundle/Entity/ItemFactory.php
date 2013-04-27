<?php

namespace Mattlab\MuseBundle\Entity;

use RuntimeException;


class ItemFactory
{
    protected $galleryRootPath;


    public function __construct($galleryRootPath)
    {
        $this->setGalleryRootPath($galleryRootPath);
    }


    public function setGalleryRootPath($galleryRootPath)
    {
        if(!file_exists($galleryRootPath))
        {
            throw new RuntimeException('"' . $galleryRootPath . '" does not exist.');
        }

        if(!is_dir($galleryRootPath))
        {
            throw new RuntimeException('"' . $galleryRootPath . '" is not a valid directory.');
        }

        if(!is_readable($galleryRootPath))
        {
            throw new RuntimeException('"' . $galleryRootPath . '" is not a readable directory.');
        }

        $this->galleryRootPath = rtrim(realpath($galleryRootPath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        return $this;
    }

    public function getGalleryRootPath()
    {
        return $this->galleryRootPath;
    }


    public function get($file)
    {
        if(is_string($file)) {
            if($file && $file{0} === '/' && file_exists($file)) {
                $file = realpath($file);

                if(is_dir($file)) {
                    $file .= DIRECTORY_SEPARATOR;
                }

                if(strpos($file, $this->getGalleryRootPath()) !== 0) {
                    throw new \RuntimeException(
                        sprintf(
                            'FORBIDDEN ! Try to access to %s (Root: %s).',
                            $file,
                            $this->getGalleryRootPath()
                        )
                    );
                }

                $relativePath = str_replace($this->getGalleryRootPath(), '', $file);

                if(is_dir($file)) {
                    return new Album($this->getGalleryRootPath(), $relativePath);
                }
                else if(substr($file, -4) === 'muse') {
                    return new EncryptedPhoto($this->getGalleryRootPath(), $relativePath);
                }
                else {
                    return new Photo($this->getGalleryRootPath(), $relativePath);
                }
            }
            else if(file_exists($fullpath = $this->getGalleryRootPath() . ltrim($file, '/'))) {
                return $this->get($fullpath);
            }
            else {
                throw new \RuntimeException($file . ' is not a valid directory or file path.');
            }
        }
        else if($file instanceof \SplFileInfo) {
            return $this->get($file->getPathname());
        }
        else {
            throw new \RuntimeException('Invalid item (type: ' . gettype($file) . ').');
        }
    }
}