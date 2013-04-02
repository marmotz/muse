<?php

namespace Muse\Entity;

class Photo extends Item {
    public function getType() {
        return self::TYPE_PHOTO;
    }
}