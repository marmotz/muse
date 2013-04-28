<?php

namespace Mattlab\MuseBundle\Tests\Units\Model;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use Mattlab\MuseBundle\Model\Album as TestedClass;


class Album extends BaseTest
{
    public function testConstruct()
    {
        $this
            ->if($album = new TestedClass($this->getGalleryRootPath(), ''))
                ->string($album->getType())
                    ->isEqualTo(TestedClass::TYPE_ALBUM)
        ;
    }
}