<?php
use Lcobucci\ActionMapper2\DependencyInjection\ContainerConfig;

return new ContainerConfig(
    __DIR__ . '/services.xml',
    __DIR__ . '/../tmp',
    '\PHPSC\Conference\Infra\DependencyInjection\Container',
    array(
        'app.baseDir' => realpath(__DIR__ . '/../') . '/'
    )
);