<?php

namespace Mattlab\MuseBundle\Tests\Units\Model;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use Mattlab\MuseBundle\Model\Photo as TestedClass;

use mock\Imagick as mockImagick;


class Photo extends BaseTest
{
    public function testConstruct()
    {
        $this
            ->if($photo = new TestedClass('', '', ''))
                ->string($photo->getType())
                    ->isEqualTo(TestedClass::TYPE_PHOTO)
        ;
    }


    public function testGetContentType()
    {
        $this
            ->if($photo = new TestedClass('', 'photo.jpg', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/jpeg')

            ->if($photo = new TestedClass('', 'photo.jpeg', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/jpeg')

            ->if($photo = new TestedClass('', 'photo.jpe', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/jpeg')

            ->if($photo = new TestedClass('', 'photo.png', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/png')

            ->if($photo = new TestedClass('', 'photo.gif', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/gif')

            ->if($photo = new TestedClass('', 'photo.bmp', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/bmp')

            ->if($photo = new TestedClass('', 'photo.webp', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/webp')

            ->if($photo = new TestedClass('', 'photo.svg', ''))
                ->string($photo->getContentType())
                    ->isEqualTo('image/svg+xml')

            ->if($photo = new TestedClass('', 'photo.' . $ext = uniqid(), ''))
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
            ->if($photo = new TestedClass('', '', ''))
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


    public function testGenerateThumb()
    {
        $this
            // ->if($photo = new TestedClass($this->getGalleryRootPath(), 'album1/photo1.jpg', $this->getCacheRootPath()))
            // ->and($mockImagick = new mockImagick($photo->getPath()))
            // ->and($this->calling($mockImagick)->getImageOrientation = Imagick::ORIENTATION_TOPRIGHT)
            // ->and($this->calling($mockImagick)->getImageWidth  = $imageWidth  = 500)
            // ->and($this->calling($mockImagick)->getImageHeight = $imageHeight = 500)
            // ->and($this->calling($mockImagick)->writeImage = function(){})
            // ->and($thumbWidth  = 200)
            // ->and($thumbHeight = 200)
            // ->and($thumbPath = $photo->getThumbPath($thumbWidth, $thumbHeight))
            //     ->string($photo->generateThumb($thumbWidth, $thumbHeight, $mockImagick))
            //         ->isEqualTo($thumbPath)
            //     ->mock($mockImagick)
            //         ->call('rotateImage')->withIdenticalArguments('white', 90)
            //             ->once()
            //         ->call('thumbnailImage')->withIdenticalArguments($thumbWidth, $thumbHeight, true)
            //             ->once()
            //         ->call('borderImage')->withIdenticalArguments('white', $thumbWidth, $thumbHeight)
            //             ->once()
            //         ->call('cropImage')->withIdenticalArguments(
            //             $thumbWidth,
            //             $thumbHeight,
            //             ($imageWidth  / 2) - ($thumbWidth  / 2),
            //             ($imageHeight / 2) - ($thumbHeight / 2)
            //         )
            //             ->once()
            //         ->call('writeImage')->withIdenticalArguments($thumbPath)
            //             ->once()
        ;
    }
}