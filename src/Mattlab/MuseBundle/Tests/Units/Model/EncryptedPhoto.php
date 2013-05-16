<?php

namespace Mattlab\MuseBundle\Tests\Units\Model;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use Mattlab\MuseBundle\Model\EncryptedPhoto as TestedClass;


class EncryptedPhoto extends BaseTest
{
    public function testGetName()
    {
        $this
            ->if($encryptedPhoto = new TestedClass('', $path = 'path/to/item.ext.muse', ''))
                ->string($encryptedPhoto->getName())
                    ->isEqualTo('item.ext')
        ;
    }
}