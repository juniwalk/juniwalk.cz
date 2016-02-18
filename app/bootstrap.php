<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

include __DIR__.'/../vendor/autoload.php';

$di = new \Nette\Configurator;
$di->addConfig(__DIR__.'/config/config.neon');
$di->setTempDirectory(__DIR__.'/../temp');
$di->addParameters([
	'pkgDir' => __DIR__.'/../vendor',
	'wwwDir' => __DIR__.'/../www',
	'appDir' => __DIR__,
]);

$di->setDebugMode(include __DIR__.'/config/config-ipconf.php');
$di->enableDebugger(__DIR__.'/../log');

$di->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

return $di->createContainer();
