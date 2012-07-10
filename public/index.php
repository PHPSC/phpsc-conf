<?php
require __DIR__ . '/../boot.php';

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\ActionMapper2\Config\ApplicationBuilder;

$app = ApplicationBuilder::build(
    __DIR__ . '/../config/routes.xml',
    __DIR__ . '/../config/services.xml',
    null,
    __DIR__ . '/../tmp',
    '\PHPSC\Conference\Infra\DependencyInjection\Container'
);

UIComponent::setDefaultBaseUrl($app->getRequest()->getBasePath());

$app->startSession('PHPSC_SSID');
$app->run();