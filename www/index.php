<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

use Nette\Application\Application;

$di = include __DIR__.'/../src/bootstrap.php';
$di->getByType(Application::class)->run();
