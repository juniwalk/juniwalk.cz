<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

use Nette\Application\Application as HttpApplication;
use Contributte\Console\Application as CliApplication;

$di = include __DIR__.'/../src/bootstrap.php';
$class = php_sapi_name() == 'cli'
    ? CliApplication::class
    : HttpApplication::class;

$di->getByType($class)->run();
