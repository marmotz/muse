<?php

namespace Muse\Controller;

use Muse\Entity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Photo {
    public function thumbAction($photo, $width, $height) {
        $photo = new Entity\Photo($photo);

        $thumbPath = $photo->getThumbPath(
            $width,
            $height
        );

        if(!file_exists($thumbPath)) {
            $path = dirname($thumbPath);

            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $thumb = new \Imagick($photo->getPath());

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
                'content-type' => $photo->getContentType()
            )
        );
    }


    public function displayAction($photo) {
        $photo = new Entity\Photo($photo);

        return new BinaryFileResponse(
            $photo->getPath(),
            200,
            array(
                'content-type' => $photo->getContentType()
            )
        );
    }
}