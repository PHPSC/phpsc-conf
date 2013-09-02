<?php
set_include_path(
    get_include_path() . PATH_SEPARATOR
    . __DIR__ . '/ui'
);

use Doctrine\Common\Annotations\AnnotationRegistry;

$autoloader = require __DIR__ . '/vendor/autoload.php';
AnnotationRegistry::registerLoader(array($autoloader, 'loadClass'));
