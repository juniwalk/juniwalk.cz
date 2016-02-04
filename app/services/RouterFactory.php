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
	 * @return RouteList
	 */
	public static function createRouter() : RouteList
	{
		Route::$defaultFlags |= Route::SECURED;

		$router = new RouteList;
		$router[] = new Route('admin/<presenter>/<action>[/<id>]', ['module' => 'Admin', 'presenter' => 'Home', 'action' => 'default']);
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
