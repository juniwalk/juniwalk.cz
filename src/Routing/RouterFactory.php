<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Routing;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
	/**
	 * @return RouteList
	 */
	public static function createRouter() : RouteList
	{
        $router = new RouteList;
        $router[] = static::getAdminModule();
        $router[] = static::getRootModule();

		return $router;
	}


	/**
	 * @return RouteList
	 */
	private static function getRootModule() : RouteList
	{
        $router = new RouteList;
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}


	/**
	 * @return RouteList
	 */
	private static function getAdminModule() : RouteList
	{
        $router = new RouteList('Admin');
		$router[] = new Route('admin/<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
