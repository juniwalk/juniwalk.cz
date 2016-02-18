<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Services\Traits;

trait BasePresenter
{
	/** @var \WebLoader\Nette\LoaderFactory @inject */
	public $webloader;

	/** @var \Nette\Localization\ITranslator @inject */
	public $translator;


	public function beforeRender()
	{
		$this->template->profile = $this->getUser()->getIdentity();
		return parent::beforeRender();
	}


	/**
	 * @return Nette\Localization\ITranslator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}


	/**
	 * @return CssLoader
	 */
	protected function createComponentCss()
	{
		return $this->webloader->createCssLoader('default');
	}


	/**
	 * @return JavaScriptLoader
	 */
	protected function createComponentJs()
	{
		return $this->webloader->createJavaScriptLoader('default');
	}
}
