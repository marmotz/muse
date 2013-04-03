<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array(
    __DIR__ . '/../src/Muse/Entity'
);
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver' => 'pdo_sqlite',
    'path'   => __DIR__ . '/../muse.sqlite',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);