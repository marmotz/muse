<?php

namespace Mattlab\MuseBundle\Adapter;

interface AdapterInjectable
{
    public function setAdapter(AdapterInterface $adapter = null);
    public function getAdapter();
}