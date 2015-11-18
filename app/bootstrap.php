<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App;

// Autoload all vendor classes
include __DIR__.'/../vendor/autoload.php';

// List of IPs with enabled debug
$enableDebugFor = [
	'2a00:1028:83c8:245e:c129:9487:69c9:8aec',  // JuniWalk-PC IPv6
	'192.168.1.1',                              // Router (local)
	'109.81.187.90',                            // Home
	'84.242.96.106',							// Design Point
];

// Create instance of new Configurator
$di = new \Nette\Configurator;
$di->addConfig(__DIR__.'/config/config.neon');
$di->setTempDirectory(__DIR__.'/../temp');
$di->addParameters([
	'wwwDir' => __DIR__.'/../www',
	'appDir' => __DIR__,
]);

// Setup debugging for this session
$di->setDebugMode($enableDebugFor);
$di->enableDebugger(__DIR__.'/../log');

// Create and register robot loader
$di->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Return instance of DI Container
return $di->createContainer();
