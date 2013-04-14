<?php

namespace Muse\Entity;

class Photo extends Item {
    public function getType() {
        return self::TYPE_PHOTO;
    }

    public function getContentType() {
        return 'image/' . substr($this->getPath(), strrpos($this->getPath(), '.') + 1);
    }

    public function getThumbPath($width, $height) {
        return sprintf(
            '%s/%s/%s_%dx%d.%s',
            $this->getCacheRootPath(),
            dirname($this->getRelativePath()),
            basename(substr($this->getRelativePath(), 0, ($dot = strrpos($this->getRelativePath(), '.')))),
            $width,
            $height,
            substr($this->getRelativePath(), $dot + 1)
        );
    }

    protected function getCacheRootPath() {
        return realpath(__DIR__ . '/../../../cache') . '/gallery';
    }
}