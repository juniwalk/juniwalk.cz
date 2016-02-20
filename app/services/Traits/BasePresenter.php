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
	/** @var \App\Controls\IAssetsFactory @inject */
	public $assets;

	/** @var \Nette\Localization\ITranslator @inject */
	public $translator;


	public function beforeRender()
	{
		$this->getTemplate()->add('appDir', $this->getContext()->parameters['appDir']);
		$this->getTemplate()->add('profile', $this->getUser()->getIdentity());
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
	 * @return App\Controls\Assets
	 */
	protected function createComponentAssets()
	{
		return $this->assets->create();
	}
}
