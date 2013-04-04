<?php

namespace Muse\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Photo {
    static public function getCacheRootPath() {
        return realpath(__DIR__ . '/../../../cache');
    }

    static public function getThumbPath($relativePhotoPath, $width, $height) {
        return sprintf(
            '%s/%s/%s_%dx%d.%s',
            self::getCacheRootPath(),
            trim(dirname($relativePhotoPath), '/'),
            basename(substr($relativePhotoPath, 0, ($dot = strrpos($relativePhotoPath, '.')))),
            $width,
            $height,
            substr($relativePhotoPath, $dot + 1)
        );
    }


    public function thumbAction($photo, $width, $height) {
        $photoRelativePath = Album::getItemRelativePath($photo);

        $thumbPath = self::getThumbPath(
            Album::getItemRelativePath($photo),
            $width,
            $height
        );

        if(!file_exists($thumbPath)) {
            $path = dirname($thumbPath);

            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $thumb = new \Imagick(Album::getItemFullPath($photo));
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

        return new BinaryFileResponse(
            $thumbPath,
            200,
            array(
                'content-type' => 'image/' . substr($thumbPath, strrpos($thumbPath, '.') + 1)
            )
        );
    }


    public function displayAction($photo) {
        $photoPath = Album::getItemFullPath($photo);

        return new BinaryFileResponse(
            $photoPath,
            200,
            array(
                'content-type' => 'image/' . substr($photoPath, strrpos($photoPath, '.') + 1)
            )
        );
    }
}