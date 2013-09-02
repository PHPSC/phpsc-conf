<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../phperrors.log');
ini_set('session.save_path', __DIR__ . '/../tmp/session');

require __DIR__ . '/../boot.php';

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\ActionMapper2\Config\ApplicationBuilder;
use Lcobucci\ActionMapper2\DependencyInjection\ContainerConfig;

$app = ApplicationBuilder::build(
    __DIR__ . '/../config/routes.xml',
    new ContainerConfig(
        __DIR__ . '/../config/services.xml',
        __DIR__ . '/../tmp',
        '\PHPSC\Conference\Infra\DependencyInjection\Container'
    ),
    null,
    'app.cache'
);

UIComponent::setBaseUrl($app->getRequest()->getBasePath());

$app->startSession('PHPSC_SSID');
$app->run();
