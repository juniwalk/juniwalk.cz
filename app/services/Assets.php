<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Services;

use WebLoader\Nette\LoaderFactory;

final class Assets extends \Nette\Application\UI\Control
{
	/** @var LoaderFactory */
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
	public function renderCss($module = 'default')
	{
		$control = $this->factory->createCssLoader($module);
		return $control->render();
	}


	/**
	 * @param  string  $module
	 * @return string
	 */
	public function renderJs($module = 'default')
	{
		$control = $this->factory->createJavaScriptLoader($module);
		return $control->render();
	}
}

interface IAssetsFactory
{
	/**
	 * @return Assets
	 */
	public function create();
}
