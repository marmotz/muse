<?php

namespace Mattlab\MuseBundle\Adapter;

trait AdapterInjector
{
    protected $adapter;


    public function setAdapter(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter()
    {
        if (null === $this->adapter) {
            $this->adapter = new Adapter();
        }

        return $this->adapter;
    }
}