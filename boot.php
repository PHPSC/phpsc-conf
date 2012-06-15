<?php
set_include_path(
    __DIR__ . '/lib' . PATH_SEPARATOR
    . __DIR__ . '/src'
);

require 'Lcobucci/Common/ClassLoader/SplAutoloader.php';

use Lcobucci\Common\ClassLoader\SplAutoloader;

$loader = new SplAutoloader();
$loader->register();
