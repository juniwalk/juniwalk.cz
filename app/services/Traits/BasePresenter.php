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


	/**
	 * @param string  $path
	 * @param array   $params
	 */
	public function redirectAjax(string $path, array $params = [])
	{
		$this->payload->postGet = TRUE;
		$this->payload->url = $this->link($path, $params);
	}


	/**
	 * @return Nette\Localization\ITranslator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}


	public function sendPayload()
	{
		if ($this->hasFlashSession()) {
			$flashes = $this->getFlashSession();
			$this->payload->flashes = iterator_to_array($flashes->getIterator());
			$flashes->remove();
		}

		parent::sendPayload();
	}


	protected function beforeRender()
	{
		$this->getTemplate()->add('appDir', $this->getContext()->parameters['appDir']);
		$this->getTemplate()->add('profile', $this->getUser()->getIdentity());
	}


	protected function afterRender()
	{
		$this->redrawControl('content');
		$this->redrawControl('title');
	}


	/**
	 * @return App\Controls\Assets
	 */
	protected function createComponentAssets()
	{
		return $this->assets->create();
	}
}
