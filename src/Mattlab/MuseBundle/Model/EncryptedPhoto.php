<?php

namespace Mattlab\MuseBundle\Model;

class EncryptedPhoto extends Photo {
    public function getName() {
        return substr(parent::getName(), 0, -5);
    }
}