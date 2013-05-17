<?php

namespace Mattlab\MuseBundle\Tests\Units\Model;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use Mattlab\MuseBundle\Model\Photo as TestedClass;

use Imagick;
use mock\Imagick as mockImagick;


class Photo extends BaseTest
{
    public function testConstruct()
    {
        $this
            ->if($photo = $this->generatePhoto(''))
                ->string($photo->getType())
                    ->isEqualTo(TestedClass::TYPE_PHOTO)
        ;
    }


    public function testGetContentType()
    {
        $this
            ->if($photo = $this->generatePhoto('photo.jpg'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/jpeg')

            ->if($photo = $this->generatePhoto('photo.jpeg'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/jpeg')

            ->if($photo = $this->generatePhoto('photo.jpe'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/jpeg')

            ->if($photo = $this->generatePhoto('photo.png'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/png')

            ->if($photo = $this->generatePhoto('photo.gif'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/gif')

            ->if($photo = $this->generatePhoto('photo.bmp'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/bmp')

            ->if($photo = $this->generatePhoto('photo.webp'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/webp')

            ->if($photo = $this->generatePhoto('photo.svg'))
                ->string($photo->getContentType())
                    ->isEqualTo('image/svg+xml')

            ->if($photo = $this->generatePhoto('photo.' . $ext = uniqid()))
                ->exception(
                    function() use($photo) {
                        $photo->getContentType();
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $ext . '" images are not supported.')
        ;
    }


    public function testGetThumbPath()
    {
        $this
            ->if($photo = $this->generatePhoto(''))
            ->and($photo->setCacheRootPath('/path/to/cache/'))
            ->and($photo->setRelativePath('/path/to/photo.jpg'))
                ->string($photo->getThumbPath(100, 100))
                    ->isEqualTo('/path/to/cache/path/to/photo_100x100.jpg')

            ->if( $photo->setCacheRootPath('/path/to/cache'))
            ->and($photo->setRelativePath('/path/to/photo.jpg'))
                ->string($photo->getThumbPath(100, 100))
                    ->isEqualTo('/path/to/cache/path/to/photo_100x100.jpg')

            ->if( $photo->setCacheRootPath('/path/to/cache/'))
            ->and($photo->setRelativePath('path/to/photo.jpg'))
                ->string($photo->getThumbPath(100, 100))
                    ->isEqualTo('/path/to/cache/path/to/photo_100x100.jpg')

            ->if( $photo->setCacheRootPath('/path/to/cache'))
            ->and($photo->setRelativePath('path/to/photo.jpg'))
                ->string($photo->getThumbPath(100, 100))
                    ->isEqualTo('/path/to/cache/path/to/photo_100x100.jpg')
        ;
    }


    /**
     * @dataProvider generateThumbDataProvider
     */
    public function testGenerateThumb($orientation, $angle)
    {
        $this
            ->if($photo = $this->generatePhoto($photoPath = 'album1/photo1.jpg'))
            ->and($mockImagick = $this->geneteMockImagick($photoPath, $orientation, $size = $this->faker->randomNumber(500, 1000)))
            ->and($thumbPath = $photo->getThumbPath($thumbSize = $this->faker->randomNumber(100, $size), $thumbSize ))
            ->and(file_exists(dirname($thumbPath)) && rmdir(dirname($thumbPath)))
                ->string($photo->generateThumb($thumbSize, $thumbSize, $mockImagick))
                    ->isEqualTo($thumbPath)
                ->mock($mockImagick)
                    ->call('thumbnailImage')->withIdenticalArguments($thumbSize, $thumbSize, true)
                        ->once()
                    ->call('borderImage')->withIdenticalArguments('white', $thumbSize, $thumbSize)
                        ->once()
                    ->call('cropImage')->withIdenticalArguments(
                        $thumbSize,
                        $thumbSize,
                        ($size / 2) - ($thumbSize / 2),
                        ($size / 2) - ($thumbSize / 2)
                    )
                        ->once()
                    ->call('writeImage')->withIdenticalArguments($thumbPath)
                        ->once()
        ;

        if ($angle === 0) {
            $this
                ->mock($mockImagick)
                    ->call('rotateImage')
                        ->never()
            ;
        }
        else {
            $this
                ->mock($mockImagick)
                    ->call('rotateImage')->withIdenticalArguments('white', $angle)
                        ->once()
            ;
        }
    }

    public function generateThumbDataProvider()
    {
        return array(
            array(Imagick::ORIENTATION_LEFTTOP,       0),
            array(Imagick::ORIENTATION_TOPLEFT,       0),
            array(Imagick::ORIENTATION_TOPRIGHT,     90),
            array(Imagick::ORIENTATION_RIGHTTOP,     90),
            array(Imagick::ORIENTATION_BOTTOMRIGHT, 180),
            array(Imagick::ORIENTATION_RIGHTBOTTOM, 180),
            array(Imagick::ORIENTATION_LEFTBOTTOM,  270),
            array(Imagick::ORIENTATION_BOTTOMLEFT,  270),
        );
    }


    /*
     * HELPERS
     */
    private function generatePhoto($photoPath)
    {
        return new TestedClass(
            $this->getGalleryRootPath(),
            $photoPath,
            $this->getCacheRootPath()
        );
    }

    private function geneteMockImagick($photoPath, $orientation, $size)
    {
        $mockImagick = new mockImagick($this->getGalleryPath($photoPath));

        $this->calling($mockImagick)->getImageOrientation = $orientation;
        $this->calling($mockImagick)->getImageWidth  = $size;
        $this->calling($mockImagick)->getImageHeight = $size;
        $this->calling($mockImagick)->writeImage = function() {};

        return $mockImagick;
    }
}