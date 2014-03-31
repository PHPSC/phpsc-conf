<?php
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\Migrations\Tools\Console\Command as Migrations;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Lcobucci\ActionMapper2\DependencyInjection\ContainerConfig;
use Lcobucci\DependencyInjection\XmlContainerBuilder;
use Lcobucci\Fixture\Console\DataFixtureHelper;
use Lcobucci\Fixture\Console\ExecuteCommand;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;

$config = require __DIR__ . '/di-container.php';

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

$commands[] = new ExecuteCommand();
$commands[] = new Migrations\DiffCommand();
$commands[] = new Migrations\ExecuteCommand();
$commands[] = new Migrations\GenerateCommand();
$commands[] = new Migrations\MigrateCommand();
$commands[] = new Migrations\StatusCommand();
$commands[] = new Migrations\VersionCommand();

return new HelperSet(
    array(
        'db' => new ConnectionHelper($em->getConnection()),
        'em' => new EntityManagerHelper($em),
        'df' => new DataFixtureHelper(
            $container->get('fixtures.config'),
            $container->get('fixtures.loader')
        ),
        'dialog' => new DialogHelper()
    )
);
