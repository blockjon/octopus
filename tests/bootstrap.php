<?php

// Load additional dependencies provided by composer.
if (!file_exists($file = __DIR__.'/../vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$loader = require_once $file;

$loader->add('Octopus', __DIR__ . '/../lib/BlockJon');
$loader->add('BlockJon\Tests', __DIR__ . '/../tests');
$loader->add('Models', __DIR__);
$loader->add('Daos', __DIR__);
