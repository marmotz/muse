<?php

namespace Mattlab\MuseBundle\Adapter;

interface AdapterInterface
{
    public function invoke($name, array $args = array());
}