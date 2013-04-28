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
            ->if($factory = new TestedClass($this->getGalleryRootPath()))
                ->string($factory->getGalleryRootPath())
                    ->isIdenticalTo(realpath($this->getGalleryRootPath()) . DIRECTORY_SEPARATOR)
        ;
    }


    public function testSetGetGalleryRootPath()
    {
        $test = $this;

        $this
            ->if($adapter = new TestAdapter)
            ->and($factory = new TestedClass($this->getGalleryRootPath(), $adapter))
                ->exception(
                    function() use($factory, &$path)
                    {
                        $factory->setGalleryRootPath($path = uniqid());
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" does not exist.')

                ->exception(
                    function() use($test, $factory, &$path)
                    {
                        $factory->setGalleryRootPath($path = $test->getGalleryRootPath() . '/album1/photo1.jpg');
                    }
                )
                    ->isInstanceOf('RuntimeException')
                    ->hasMessage('"' . $path . '" is not a valid directory.')

            ->if($adapter->is_readable = false)
                ->exception(
                    function() use($test, $factory, &$path)
                    {
                        $factory->setGalleryRootPath($path = $test->getGalleryRootPath() . '/album2');
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


    public function testGet()
    {
        $this
            ->if($factory = new TestedClass($this->getGalleryRootPath()))
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