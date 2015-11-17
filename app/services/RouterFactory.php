<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Services;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
	/**
	 * Create new list of routes.
	 * @return RouteList
	 */
	public static function createRouter()
	{
		// Make this site HTTPS only
		Route::$defaultFlags |= Route::SECURED;

		// Create new Router instance
		$router = new RouteList;
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
