<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../tmp/phperrors.log');
//ini_set('session.save_path', __DIR__ . '/../tmp/session');

require __DIR__ . '/../boot.php';

use Lcobucci\ActionMapper2\Config\ApplicationBuilder;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Infra\Errors\ErrorHandler;

$app = ApplicationBuilder::build(
    __DIR__ . '/../config/routes.xml',
    require __DIR__ . '/../config/di-container.php',
    new ErrorHandler(),
    'app.cache'
);

UIComponent::setBaseUrl($app->getRequest()->getBasePath());

$app->startSession('PHPSC_SSID');
$app->run();
