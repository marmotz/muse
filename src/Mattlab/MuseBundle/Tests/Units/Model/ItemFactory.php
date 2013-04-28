<?php

namespace Mattlab\MuseBundle\Tests\Units\Model;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use Mattlab\MuseBundle\Adapter\TestAdapter;
use Mattlab\MuseBundle\Model\ItemFactory as TestedClass;
use SplFileInfo;


class ItemFactory extends BaseTest
{
    public function testConstruct()
    {
        $this
            ->if($factory = new TestedClass($this->getGalleryRootPath(), $this->getCacheRootPath()))
                ->string($factory->getGalleryRootPath())
                    ->isIdenticalTo(realpath($this->getGalleryRootPath()) . DIRECTORY_SEPARATOR)
                ->string($factory->getCacheRootPath())
                    ->isIdenticalTo(realpath($this->getCacheRootPath()) . DIRECTORY_SEPARATOR)
        ;
    }


    public function testSetGetGalleryRootPath()
    {
        $test = $this;

        $this
            ->if($adapter = new TestAdapter)
            ->and($factory = new TestedClass($this->getGalleryRootPath(), $this->getCacheRootPath(), $adapter))
            ->and($adapter->file_exists = false)
            ->and($adapter->is_dir      = false)
            ->and($adapter->is_readable = false)
                ->exception(
                    function() use($factory, &$path)
                    {
                        $factory->setGalleryRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" does not exist.')

            ->if( $adapter->file_exists = true)
            ->and($adapter->is_dir      = false)
            ->and($adapter->is_readable = false)
                ->exception(
                    function() use($test, $factory, &$path)
                    {
                        $factory->setGalleryRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" is not a valid directory.')


            ->if( $adapter->file_exists = true)
            ->and($adapter->is_dir      = true)
            ->and($adapter->is_readable = false)
                ->exception(
                    function() use($test, $factory, &$path)
                    {
                        $factory->setGalleryRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" is not a readable directory.')

            ->if($adapter->reset())
                ->object($factory->setGalleryRootPath($this->getGalleryRootPath()))
                    ->isIdenticalTo($factory)
                ->string($factory->getGalleryRootPath())
                    ->isIdenticalTo(realpath($this->getGalleryRootPath()) . DIRECTORY_SEPARATOR)
        ;
    }


    public function testSetGetCacheRootPath()
    {
        $test = $this;

        $this
            ->if($adapter = new TestAdapter)
            ->and($factory = new TestedClass($this->getGalleryRootPath(), $this->getCacheRootPath(), $adapter))
            ->and($adapter->file_exists = false)
            ->and($adapter->is_dir      = false)
            ->and($adapter->is_readable = false)
                ->exception(
                    function() use($factory, &$path)
                    {
                        $factory->setCacheRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" does not exist.')

            ->if( $adapter->file_exists = true)
            ->and($adapter->is_dir      = false)
            ->and($adapter->is_readable = false)
                ->exception(
                    function() use($test, $factory, &$path)
                    {
                        $factory->setCacheRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" is not a valid directory.')

            ->if( $adapter->file_exists = true)
            ->and($adapter->is_dir      = true)
            ->and($adapter->is_readable = false)
                ->exception(
                    function() use($test, $factory, &$path)
                    {
                        $factory->setCacheRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" is not a readable directory.')

            ->if($adapter->reset())
                ->object($factory->setCacheRootPath($this->getCacheRootPath()))
                    ->isIdenticalTo($factory)
                ->string($factory->getCacheRootPath())
                    ->isIdenticalTo(realpath($this->getCacheRootPath()) . DIRECTORY_SEPARATOR)
        ;
    }


    public function testGet()
    {
        $this
            ->if($factory = new TestedClass($this->getGalleryRootPath(), $this->getCacheRootPath()))
                ->exception(
                    function() use($factory)
                    {
                        $factory->get('..');
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage(
                        sprintf(
                            'FORBIDDEN ! Try to access to %s (Root: %s).',
                            realpath($this->getGalleryRootPath() . '/..') . DIRECTORY_SEPARATOR,
                            realpath($this->getGalleryRootPath()) . DIRECTORY_SEPARATOR
                        )
                    )

                ->exception(
                    function() use($factory, &$path)
                    {
                        $factory->get($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage($path . ' is not a valid directory or file path.')

                ->exception(
                    function() use($factory)
                    {
                        $factory->get(42);
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('Invalid item (type: integer).')

                ->object($item = $factory->get($path = ''))
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Album')
                ->string($item->getPath())
                    ->isEqualTo($this->getGalleryPath($path) . '/')

                ->object($item = $factory->get($path = $this->getGalleryRootPath()))
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Album')
                ->string($item->getPath())
                    ->isEqualTo(realpath($path) . '/')

                ->object($item = $factory->get($path = 'album1'))
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Album')
                ->string($item->getPath())
                    ->isEqualTo($this->getGalleryPath($path))

                ->object($item = $factory->get($path = 'album1/photo1.jpg'))
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Photo')
                ->string($item->getPath())
                    ->isEqualTo($this->getGalleryPath($path))

                ->object($item = $factory->get($path = 'album1/album1.1/photo1.jpg.muse'))
                    ->isInstanceOf('Mattlab\MuseBundle\Model\EncryptedPhoto')
                ->string($item->getPath())
                    ->isEqualTo($this->getGalleryPath($path))

                ->object($item = $factory->get(new SplFileInfo($path = $this->getGalleryPath('album1'))))
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Album')
                ->string($item->getPath())
                    ->isEqualTo($path)
        ;
    }
}