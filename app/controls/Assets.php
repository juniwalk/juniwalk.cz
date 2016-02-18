<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Controls;

use WebLoader\Nette\LoaderFactory;

/**
 * @usage {control assets:css frontend}
 *			{control assets:js backend}
 */
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
		try {
			$control = $this->factory->createCssLoader($module);

		} catch (\Exception $e) {
			// ERROR
		}

		return $control->render();
	}


	/**
	 * @param  string  $module
	 * @return string
	 */
	public function renderJs($module = 'default')
	{
		try {
			$control = $this->factory->createJavaScriptLoader($module);

		} catch (\Exception $e) {
			// ERROR
		}

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
