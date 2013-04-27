<?php

namespace Mattlab\MuseBundle\Tests\Units;

use atoum\AtoumBundle\Test\Units\Test;


class BaseTest extends Test
{
    protected function getGalleryRootPath()
    {
        return __DIR__ . '/../../Resources/tests/gallery';
    }

    protected function getGalleryPath($item)
    {
        $item = realpath($this->getGalleryRootPath() . '/' . ltrim($item, '/'));

        return $item;
    }
}