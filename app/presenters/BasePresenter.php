<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

abstract class BasePresenter extends \Nittro\Bridges\NittroUI\Presenter
{
	/** @var \App\Services\IAssetsFactory @inject */
	public $assets;

	/** @var \Nette\Localization\ITranslator @inject */
	public $translator;


	/**
	 * @return Nette\Localization\ITranslator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}


	protected function startup()
	{
		$this->setDefaultSnippets(['content', 'title']);
		parent::startup();
	}


	protected function beforeRender()
	{
		$this->getTemplate()->add('appDir', $this->getContext()->parameters['appDir']);
		$this->getTemplate()->add('profile', $this->getUser()->getIdentity());
	}


	/**
	 * @return App\Services\Assets
	 */
	protected function createComponentAssets()
	{
		return $this->assets->create();
	}
}
