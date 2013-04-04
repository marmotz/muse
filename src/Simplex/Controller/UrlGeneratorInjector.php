<?php

namespace Simplex\Controller;

use Symfony\Component\Routing\Generator\UrlGenerator;

trait UrlGeneratorInjector {
    protected $urlGenerator;


    public function setUrlGenerator(UrlGenerator $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    public function getUrlGenerator() {
        return $this->urlGenerator;
    }
}