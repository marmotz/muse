<?php

namespace Simplex\Entity;

trait Paginator {
    protected $page;
    protected $nbPerPage;


    public function paginate($page, $nbPerPage) {
        $this->page      = $page;
        $this->nbPerPage = $nbPerPage;
    }

    public function getPaginatedPreviousData() {
        return array_slice(
            $this->getCollection(),
            0,
            ($this->page - 1) * $this->nbPerPage
        );
    }

    public function getPaginatedData() {
        return array_slice(
            $this->getCollection(),
            ($this->page - 1) * $this->nbPerPage,
            $this->nbPerPage
        );
    }

    public function getPaginatedNextData() {
        return array_slice(
            $this->getCollection(),
            ($this->page - 1) * $this->nbPerPage + $this->nbPerPage
        );
    }

    public function getNbPages() {
        return ceil(count($this->getCollection()) / $this->nbPerPage);
    }

    public function getFirstPage() {
        return 1;
    }

    public function getPreviousPage() {
        return $this->page - 1;
    }

    public function hasPreviousPage() {
        return $this->getPreviousPage() > 0;
    }

    public function getPages($nb = 0) {
        if($nb === 0) {
            $start = 1;
            $stop = $this->getNbPages();
        }
        else {
            $start = max(1,                   $this->page - floor($nb / 2));
            $stop  = min($this->getNbPages(), $start + $nb - 1);
            $start = max(1,                   $stop - $nb + 1);
        }

        return range($start, $stop);
    }

    public function hasNextPage() {
        return $this->getNextPage() <= $this->getNbPages();
    }

    public function getNextPage() {
        return $this->page + 1;
    }

    public function getLastPage() {
        return $this->getNbPages();
    }

    abstract public function getCollection();
}