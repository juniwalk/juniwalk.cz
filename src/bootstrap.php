<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
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
