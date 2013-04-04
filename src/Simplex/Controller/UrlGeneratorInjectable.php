<?php

namespace Simplex\Controller;

use Symfony\Component\Routing\Generator\UrlGenerator;

interface UrlGeneratorInjectable {
    public function setUrlGenerator(UrlGenerator $em);
    public function getUrlGenerator();
}