<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App;

include __DIR__.'/../vendor/autoload.php';

$enableDebugFor = [
	'192.168.1.1',		// Router (local)
	'109.81.187.90',	// Home
	'84.242.96.106',	// Design Point
];

$di = new \Nette\Configurator;
$di->addConfig(__DIR__.'/config/config.neon');
$di->setTempDirectory(__DIR__.'/../temp');
$di->addParameters([
	'wwwDir' => __DIR__.'/../www',
	'appDir' => __DIR__,
]);

$di->setDebugMode($enableDebugFor);
$di->enableDebugger(__DIR__.'/../log');

$di->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

return $di->createContainer();
