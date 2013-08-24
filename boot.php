<?php
set_include_path(
    __DIR__ . '/lib' . PATH_SEPARATOR
    . __DIR__ . '/ui'
);

use Doctrine\Common\Annotations\AnnotationRegistry;

$autoloader = require __DIR__ . '/vendor/autoload.php';
$autoloader->setUseIncludePath(true); // temporary
AnnotationRegistry::registerLoader(array($autoloader, 'loadClass'));
