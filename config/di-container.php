<?php
use Lcobucci\DependencyInjection\XmlContainerBuilder;

$builder = new XmlContainerBuilder();

return $builder->getContainer(require __DIR__ . '/di-config.php');