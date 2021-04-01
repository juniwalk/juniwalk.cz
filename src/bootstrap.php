<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

include __DIR__.'/../vendor/autoload.php';

$configurator = new \Nette\Configurator;
$configurator->addConfig(__DIR__.'/../config/config.neon');
$configurator->setTempDirectory(__DIR__.'/../temp');
$configurator->addParameters([
	'wwwDir' => __DIR__.'/../www',
	'appDir' => __DIR__,
]);

$configurator->setDebugMode(@include __DIR__.'/../config/config-ipconf.php');
$configurator->enableDebugger(__DIR__.'/../log');

return $configurator->createContainer();
