<?php
require __DIR__ . '/boot.php';

use Lcobucci\DependencyInjection\XmlContainerBuilder;
use Lcobucci\ActionMapper2\DependencyInjection\ContainerConfig;
use PHPSC\Conference\Domain\Entity\Logo;

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

if (!isset($argv[1]) || !file_exists($argv[1])) {
    echo 'Valid image path must be informed' . PHP_EOL;
    exit(1);
}

$logo = new Logo();
$logo->setImage(fopen($argv[1], 'rb'));
$logo->setCreatedAt(new \DateTime());

$em = $container->get('entityManager');

$em->persist($logo);
$em->flush();
