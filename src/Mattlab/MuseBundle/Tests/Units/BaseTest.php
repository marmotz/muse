<?php

namespace Mattlab\MuseBundle\Tests\Units;

use atoum\AtoumBundle\Test\Units\Test;


class BaseTest extends Test
{
    public function getGalleryRootPath()
    {
        return __DIR__ . '/../../Resources/tests/gallery';
    }

    public function getGalleryPath($item)
    {
        $item = realpath($this->getGalleryRootPath() . '/' . ltrim($item, '/'));

        return $item;
    }


    public function getCacheRootPath()
    {
        return __DIR__ . '/../../Resources/tests/cache';
    }
}