<?php

namespace Muse\Entity;

use Simplex\Entity\Paginator;

class ItemCollection implements \Iterator {
    protected $collection;

    protected $pointer;

    public function current() {
        return $this->collection[$this->pointer];
    }

    public function key() {
        return $this->pointer;
    }

    public function next() {
        $this->pointer++;
    }

    public function rewind() {
        $this->pointer = 0;
    }

    public function valid() {
        return isset($this->collection[$this->pointer]);
    }


    use Paginator;


    public function addItems($items) {
        foreach($items as $item) {
            $this->addItem($item, false);
        }
    }

    public function addItem($item) {
        if($item instanceof \SplFileInfo) {
            $item = $item->isDir() ? new Album($item) : new Photo($item);
        }
        else if(!($item instanceof Item)) {
            throw new \RuntimeException('$item must be an instance Muse\Entity\Item');
        }

        $this->collection[] = $item;
    }

    public function getCollection() {
        return $this->collection;
    }
}