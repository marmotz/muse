<?php

namespace Simplex\Twig\Extension;

class Asset extends \Twig_Extension {
    protected $rootUrl;


    public function __construct($rootUrl) {
        $this->rootUrl = rtrim($rootUrl, '/');
    }

    public function getFunctions() {
        return array(
            'asset' => new \Twig_Function_Method($this, 'asset'),
        );
    }

    public function asset($url) {
        return sprintf(
            '%s/%s',
            $this->rootUrl,
            ltrim($url, '/')
        );
    }

    public function getName() {
        return 'asset';
    }
}