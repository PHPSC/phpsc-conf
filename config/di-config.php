<?php
use Lcobucci\ActionMapper2\DependencyInjection\ContainerConfig;

return new ContainerConfig(
    __DIR__ . '/services.xml',
    __DIR__ . '/../tmp',
    array(
        'app.baseDir' => realpath(__DIR__ . '/../') . '/'
    ),
    '\PHPSC\Conference\Infra\DependencyInjection\Container'
);