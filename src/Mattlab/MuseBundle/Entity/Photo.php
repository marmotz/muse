<?php

namespace Mattlab\MuseBundle\Entity;

class Photo extends Item {
    public function getType() {
        return self::TYPE_PHOTO;
    }

    public function getContentType() {
        return 'image/' . substr($this->getPath(), strrpos($this->getPath(), '.') + 1);
    }

    public function getThumbPath($width, $height) {
        return sprintf(
            '%s/%s/%s_%dx%d.%s',
            $this->getCacheRootPath(),
            dirname($this->getRelativePath()),
            basename(substr($this->getRelativePath(), 0, ($dot = strrpos($this->getRelativePath(), '.')))),
            $width,
            $height,
            substr($this->getRelativePath(), $dot + 1)
        );
    }

    protected function getCacheRootPath() {
        return realpath(__DIR__ . '/../../../cache') . '/gallery';
    }


    public function generateThumb($width, $height) {
        $thumbPath = $this->getThumbPath(
            $width,
            $height
        );

        if(!file_exists($thumbPath)) {
            $path = dirname($thumbPath);

            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $thumb = new \Imagick($this->getPath());

            switch($thumb->getImageOrientation()) {
                case \Imagick::ORIENTATION_TOPRIGHT:
                case \Imagick::ORIENTATION_RIGHTTOP:
                    $thumb->rotateImage('white', 90);
                break;
                case \Imagick::ORIENTATION_BOTTOMRIGHT:
                case \Imagick::ORIENTATION_RIGHTBOTTOM:
                    $thumb->rotateImage('white', 180);
                break;
                case \Imagick::ORIENTATION_BOTTOMLEFT:
                case \Imagick::ORIENTATION_LEFTBOTTOM:
                    $thumb->rotateImage('white', 270);
                break;
            }

            $thumb->thumbnailImage($width, $height, true);
            $thumb->borderImage('white', $width, $height);
            $thumb->cropImage(
                $width,
                $height,
                ($thumb->getImageWidth()  / 2) - ($width  / 2),
                ($thumb->getImageHeight() / 2) - ($height / 2)
            );
            $thumb->writeImage($thumbPath);
        }

        return $thumbPath;
    }
}