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


    public function addItems($items, $repaginate = true) {
        foreach($items as $item) {
            $this->addItem($item, false);
        }

        if($repaginate) {
            $this->repaginateOnCollectionChange();
        }
    }

    public function addItem($item, $repaginate = true) {
        if($item instanceof \SplFileInfo) {
            $item = $item->isDir() ? new Album($item) : new Photo($item);
        }
        else if(!($item instanceof Item)) {
            throw new \RuntimeException('$item must be an instance Muse\Entity\Item');
        }

        $this->collection[] = $item;

        if($repaginate) {
            $this->repaginateOnCollectionChange();
        }
    }

    public function getCollection() {
        return $this->collection;
    }

    public function setCollection(array $collection, $repaginate = true) {
        $this->collection = array();

        $this->addItems($collection, $repaginate);
    }
}