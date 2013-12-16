<?php
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Lcobucci\ActionMapper2\DependencyInjection\ContainerConfig;
use Lcobucci\DependencyInjection\XmlContainerBuilder;
use Symfony\Component\Console\Helper\HelperSet;

$config = require __DIR__ . '/config/di-container.php';

$builder = new XmlContainerBuilder(
    ContainerConfig::getClass($config),
    ContainerConfig::getDumpDir($config)
);

$container = $builder->getContainer(
    $config->getFile(),
    array(),
    $config->getDefaultParameters()
);

$em = $container->get('entitymanager');

return new HelperSet(
    array(
        'db' => new ConnectionHelper($em->getConnection()),
        'em' => new EntityManagerHelper($em)
    )
);
