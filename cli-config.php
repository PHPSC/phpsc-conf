<?php
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Lcobucci\DependencyInjection\XmlContainerBuilder;
use Symfony\Component\Console\Helper\HelperSet;

$builder = new XmlContainerBuilder(
    'PHPSC\Conference\Infra\DependencyInjection\Container',
    __DIR__ . '/tmp'
);

$container = $builder->getContainer(__DIR__ . '/config/services.xml');
$em = $container->get('entitymanager');

return new HelperSet(
    array(
        'db' => new ConnectionHelper($em->getConnection()),
        'em' => new EntityManagerHelper($em)
    )
);
