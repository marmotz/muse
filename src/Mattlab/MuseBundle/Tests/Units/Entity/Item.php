<?php

namespace Mattlab\MuseBundle\Tests\Units\Entity;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use mock\Mattlab\MuseBundle\Entity\Item as TestedClass;


class Item extends BaseTest
{
    public function testConstruct()
    {
        $this
            ->if($item = new TestedClass($this->getGalleryRootPath(), ''))
                ->string($item->getGalleryRootPath())
                    ->isEqualTo($this->getGalleryRootPath())
                ->string($item->getRelativePath())
                    ->isEqualTo('')
        ;
    }


    public function testSetGetGalleryRootPath()
    {
        $this
            ->if($item = new TestedClass('', ''))
                ->object($item->setGalleryRootPath($path = uniqid()))
                    ->isIdenticalTo($item)
                ->string($item->getGalleryRootPath())
                    ->isEqualTo($path)
        ;
    }


    public function testSetGetRelativePath()
    {
        $this
            ->if($item = new TestedClass('', ''))
                ->object($item->setRelativePath($path = uniqid()))
                    ->isIdenticalTo($item)
                ->string($item->getRelativePath())
                    ->isEqualTo($path)
        ;
    }


    public function testGetBasename()
    {
        $this
            ->if($item = new TestedClass('', $path = 'path/to/item'))
                ->string($item->getBasename())
                    ->isEqualTo(basename($path))
        ;
    }


    public function testGetName()
    {
        $this
            ->if($item = new TestedClass('', $path = 'path/to/item'))
                ->string($item->getName())
                    ->isEqualTo($item->getBasename())
        ;
    }


    public function testGetPath()
    {
        $this
            ->if($item = new TestedClass($root = uniqid(), $path = uniqid()))
                ->string($item->getPath())
                    ->isEqualTo($root . $path)
        ;
    }


    public function testIsAlbum()
    {
        $this
            ->if($item = new TestedClass('', ''))
            ->and($item->getMockController()->getType = TestedClass::TYPE_ALBUM)
                ->boolean($item->isAlbum())
                    ->isTrue()

            ->if($item->getMockController()->getType = TestedClass::TYPE_PHOTO)
                ->boolean($item->isAlbum())
                    ->isFalse()
        ;
    }


    public function testIsPhoto()
    {
        $this
            ->if($item = new TestedClass('', ''))
            ->and($item->getMockController()->getType = TestedClass::TYPE_PHOTO)
                ->boolean($item->isPhoto())
                    ->isTrue()

            ->if($item->getMockController()->getType = TestedClass::TYPE_ALBUM)
                ->boolean($item->isPhoto())
                    ->isFalse()
        ;
    }


    public function testGetParentPaths()
    {
        $this
            ->if($item = new TestedClass('', $path = 'path/to/item'))
                ->array($item->getParentPaths())
                    ->isEqualTo(
                        array(
                            'path/to',
                            'path',
                        )
                    )
        ;
    }
}