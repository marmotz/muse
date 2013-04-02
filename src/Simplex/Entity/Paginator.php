<?php

namespace Simplex\Entity;

trait Paginator {
    protected $nbPages;
    protected $total;

    protected $savedPage;
    protected $savedNbPerPage;


    public function paginate($page, $nbPerPage) {
        $this->savedPage = $page;
        $this->savedNbPerPage = $nbPerPage;

        $this->total   = count($this->getCollection());
        $this->nbPages = ceil($this->total / $nbPerPage);

        $this->setCollection(
            array_slice(
                $this->getCollection(),
                ($page - 1) * $nbPerPage,
                $nbPerPage
            ),
            false
        );
    }

    public function repaginateOnCollectionChange() {
        if($this->savedPage !== null && $this->savedNbPerPage !== null) {
            $this->paginate($this->savedPage, $this->savedNbPerPage);
        }
    }

    public function getNbPages() {
        return $this->nbPages;
    }

    abstract public function getCollection();
    abstract public function setCollection(array $collection, $repaginate = true);
}