<?php

namespace Muse\Entity;

class Album extends Item {
    public function getType() {
        return self::TYPE_ALBUM;
    }
}