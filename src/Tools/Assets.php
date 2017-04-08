<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Tools;

use WebLoader\Nette\LoaderFactory;

final class Assets extends \Nette\Application\UI\Control
{
	/**
	 * @var LoaderFactory
	 */
	private $factory;


	/**
	 * @param LoaderFactory  $factory
	 */
	public function __construct(LoaderFactory $factory)
	{
		$this->factory = $factory;
	}


	/**
	 * @param  string  $module
	 * @return string
	 */
	public function renderCss(string $module = 'default')
	{
		return $this->factory->createCssLoader($module)->render();
	}


	/**
	 * @param  string  $module
	 * @return string
	 */
	public function renderJs(string $module = 'default')
	{
		return $this->factory->createJavaScriptLoader($module)->render();
	}
}
