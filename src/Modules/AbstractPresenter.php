<?php

/**
 * @author    Design Point, s.r.o. <info@dpoint.cz>
 * @package   design-point/max
 * @link      https://gitlab.com/design-point/max
 * @copyright Design Point, s.r.o. (c) 2016
 * @license   MIT License
 */

namespace App\Modules;

use App\Security\AccessManager;
use App\Tools\AssetsFactory;
use Nette\Localization\ITranslator;

abstract class AbstractPresenter extends \Nette\Application\UI\Presenter
{
	/**
	 * @var AccessManager
	 */
	private $accessManager;

	/**
	 * @var AssetsFactory
	 */
	private $assets;

	/**
	 * @var ITranslator
	 */
	private $translator;


	/**
	 * @param IAssetsFactory  $assets
	 */
	public function injectAccessManager(AccessManager $accessManager)
	{
		$this->accessManager = $accessManager;
	}


	/**
	 * @param AssetsFactory  $assets
	 */
	public function injectAssets(AssetsFactory $assets)
	{
		$this->assets = $assets;
	}


	/**
	 * @param ITranslator  $translator
	 */
	public function injectTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}


	/**
	 * @return ITranslator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}


	/**
	 * @return AccessManager
	 */
	public function getAccessManager() : AccessManager
	{
		return $this->accessManager;
	}


	protected function startup()
	{
/*
		$token = $this->getParameter('token');
		$user = $this->getUser();

		if ($token && $entity = $this->accessManager->validateToken($this, $token)) {
			$user->setExpiration(0, TRUE, TRUE);
			$user->login($entity);
		}

		if (!$user->isLoggedIn() && !$this instanceof AuthPresenter) {
			if ($user->getLogoutReason() === $user::INACTIVITY) {
				$this->flashMessage('servis.auth.signedOut', 'warning');
			}

			$this->redirect(':Auth:signIn', ['redirect' => $this->storeRequest()]);
		}

		$entity = $user->getIdentity();

		if (!$user->isAllowed($this->getName(), $this->getAction()) || ($entity && $entity->isBanned())) {
			return $this->error('You don\'t have access to this section!', 403);
		}

		$this->redrawControl('flashMessages');
*/
		return parent::startup();
	}


	protected function beforeRender()
	{
		$this->getTemplate()->add('appDir', $this->getContext()->parameters['appDir']);
		$this->getTemplate()->add('profile', $this->getUser()->getIdentity());
		$this->getTemplate()->add('locale', $this->translator->getLocale());

		return parent::beforeRender();
	}


	/**
	 * @param  \Nette\Application\IResponse  $response
	 */
	protected function shutdown($response)
	{
		if (!$token = $this->getParameter('token')) {
			return NULL;
		}

		$this->getUser()->logout(TRUE);
		$this->getSession()->destroy();
	}


	/**
	 * @param  string  $name
	 * @return \App\Services\Assets
	 */
	protected function createComponentAssets(string $name)
	{
		return $this->assets->create();
	}
}
