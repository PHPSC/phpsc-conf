<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../phperrors.log');
ini_set('session.save_path', __DIR__ . '/../tmp/session');

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