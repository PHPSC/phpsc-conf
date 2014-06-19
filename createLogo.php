<?php
require __DIR__ . '/boot.php';

use PHPSC\Conference\Domain\Entity\Logo;


if (!isset($argv[1]) || !file_exists($argv[1])) {
    echo 'Valid image path must be informed' . PHP_EOL;
    exit(1);
}

$logo = new Logo();
$logo->setImage(fopen($argv[1], 'rb'));
$logo->setCreatedAt(new \DateTime());

$container = require __DIR__ . '/config/di-container.php';
$em = $container->get('entityManager');

$em->persist($logo);
$em->flush();
