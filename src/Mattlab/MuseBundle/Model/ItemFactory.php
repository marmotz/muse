<?php

namespace Mattlab\MuseBundle\Model;

use RuntimeException;
use Mattlab\MuseBundle\Adapter\AdapterInjectable;
use Mattlab\MuseBundle\Adapter\AdapterInjector;
use Mattlab\MuseBundle\Adapter\AdapterInterface;


class ItemFactory implements AdapterInjectable
{
    use AdapterInjector;


    protected $galleryRootPath;
    protected $cacheRootPath;


    public function __construct($galleryRootPath, $cacheRootPath, AdapterInterface $adapter = null)
    {
        $this
            ->setAdapter($adapter)
            ->setGalleryRootPath($galleryRootPath)
            ->setCacheRootPath($cacheRootPath)
        ;
    }


    public function setGalleryRootPath($galleryRootPath)
    {
        if(!$this->getAdapter()->file_exists($galleryRootPath))
        {
            throw new RuntimeException('"' . $galleryRootPath . '" does not exist.');
        }

        if(!$this->getAdapter()->is_dir($galleryRootPath))
        {
            throw new RuntimeException('"' . $galleryRootPath . '" is not a valid directory.');
        }

        if(!$this->getAdapter()->is_readable($galleryRootPath))
        {
            throw new RuntimeException('"' . $galleryRootPath . '" is not a readable directory.');
        }

        $this->galleryRootPath = realpath($galleryRootPath) . DIRECTORY_SEPARATOR;

        return $this;
    }

    public function getGalleryRootPath()
    {
        return $this->galleryRootPath;
    }


    public function setCacheRootPath($cacheRootPath)
    {
        if(!$this->getAdapter()->file_exists($cacheRootPath))
        {
            throw new RuntimeException('"' . $cacheRootPath . '" does not exist.');
        }

        if(!$this->getAdapter()->is_dir($cacheRootPath))
        {
            throw new RuntimeException('"' . $cacheRootPath . '" is not a valid directory.');
        }

        if(!$this->getAdapter()->is_readable($cacheRootPath))
        {
            throw new RuntimeException('"' . $cacheRootPath . '" is not a readable directory.');
        }

        $this->cacheRootPath = realpath($cacheRootPath) . DIRECTORY_SEPARATOR;

        return $this;
    }

    public function getcacheRootPath()
    {
        return $this->cacheRootPath;
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
                    $class = 'Album';
                }
                else if(substr($file, -4) === 'muse') {
                    $class = 'EncryptedPhoto';
                }
                else {
                    $class = 'Photo';
                }

                $class = __NAMESPACE__ . '\\' . $class;

                return new $class($this->getGalleryRootPath(), $relativePath, $this->getCacheRootPath());
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