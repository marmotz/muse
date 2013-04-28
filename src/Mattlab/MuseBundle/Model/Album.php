<?php

namespace Mattlab\MuseBundle\Model;

class Album extends Item {
    public function getType() {
        return self::TYPE_ALBUM;
    }
}