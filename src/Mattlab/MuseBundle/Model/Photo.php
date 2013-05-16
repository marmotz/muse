<?php

namespace Mattlab\MuseBundle\Model;

use Imagick;

class Photo extends Item {
    public function getType() {
        return self::TYPE_PHOTO;
    }


    public function getContentType() {
        switch($extension = substr($this->getPath(), strrpos($this->getPath(), '.') + 1)) {
            case 'jpg':
            case 'jpeg':
            case 'jpe':
                return 'image/jpeg';
            break;

            case 'svg':
                return 'image/svg+xml';
            break;

            case 'bmp':
            case 'gif':
            case 'png':
            case 'webp':
                return 'image/' . $extension;
            break;

            default:
                throw new \RuntimeException('"' . $extension . '" images are not supported.');
        }
    }


    public function getThumbPath($width, $height) {
        return sprintf(
            '%s/%s/%s_%dx%d.%s',
            rtrim($this->getCacheRootPath(), DIRECTORY_SEPARATOR),
            dirname($this->getRelativePath()),
            basename(substr($this->getRelativePath(), 0, ($dot = strrpos($this->getRelativePath(), '.')))),
            $width,
            $height,
            substr($this->getRelativePath(), $dot + 1)
        );
    }


    public function generateThumb($width, $height, Imagick $imagick = null) {
        $thumbPath = $this->getThumbPath(
            $width,
            $height
        );

        if(!file_exists($thumbPath)) {
            $path = dirname($thumbPath);

            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imagick = $imagick ?: new Imagick($this->getPath());

            switch($imagick->getImageOrientation()) {
                case Imagick::ORIENTATION_TOPRIGHT:
                case Imagick::ORIENTATION_RIGHTTOP:
                    $imagick->rotateImage('white', 90);
                break;
                case Imagick::ORIENTATION_BOTTOMRIGHT:
                case Imagick::ORIENTATION_RIGHTBOTTOM:
                    $imagick->rotateImage('white', 180);
                break;
                case Imagick::ORIENTATION_BOTTOMLEFT:
                case Imagick::ORIENTATION_LEFTBOTTOM:
                    $imagick->rotateImage('white', 270);
                break;
            }

            $imagick->thumbnailImage($width, $height, true);
            $imagick->borderImage('white', $width, $height);
            $imagick->cropImage(
                $width,
                $height,
                ($imagick->getImageWidth()  / 2) - ($width  / 2),
                ($imagick->getImageHeight() / 2) - ($height / 2)
            );
            $imagick->writeImage($thumbPath);
        }

        return $thumbPath;
    }
}