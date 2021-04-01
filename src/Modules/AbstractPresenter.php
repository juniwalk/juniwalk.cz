<?php declare(strict_types=1);

/**
 * @copyright (c) Martin Procházka
 * @license   MIT License
 */

namespace App\Modules;

use App\Entity\User;
use JuniWalk\Tessa\BundleManager;
use JuniWalk\Tessa\TessaControl;
use Nette\Application\UI\Presenter;
use Nette\Security\IUserStorage;
use Nette\Utils\Strings;

abstract class AbstractPresenter extends Presenter
{
    /** @var BundleManager */
    private $bundleManager;


	/**
	 * @param  BundleManager  $bundleManager
	 * @return void
	 */
	public function injectBundleManager(BundleManager $bundleManager): void
	{
        $this->bundleManager = $bundleManager;
	}


	/**
	 * @return User|null
	 */
	public function getProfile(): ?User
	{
		return $this->getUser()->getIdentity();
	}


    /**
     * @return string
     */
    public function getPageName(): string
    {
        return $this->getName().':'.$this->getAction();
    }


	/**
	 * @return bool
	 */
	public function hasFlashMessages(): bool
	{
		$flashSession = $this->getFlashSession();
		$id = $this->getParameterId('flash');

		return !empty($flashSession->$id);
	}


	protected function startup()
	{
		$user = $this->getUser();
		$profile = $user->getIdentity();

		if (!$user->isLoggedIn() && !$user->isAllowed($this->getName(), $this->getAction())) {
			if ($user->getLogoutReason() === IUserStorage::INACTIVITY) {
				$this->redirect(':Auth:lockscreen', ['redirect' => $this->storeRequest()]);
			}

			$this->redirect(':Auth:signIn', ['redirect' => $this->storeRequest()]);
		}

		if (!$user->isAllowed($this->getName(), $this->getAction()) || ($profile && !$profile->isActive())) {
			throw new ForbiddenRequestException('You don\'t have access to '.$this->getPageName().'!', 403);
		}

		return parent::startup();
	}


	protected function beforeRender()
	{
		if ($this->hasFlashMessages() && !$this->isControlInvalid()) {
			$this->redrawControl('flashMessages');
		}

		$template = $this->getTemplate();
		$template->add('appDir', @$this->getContext()->parameters['appDir']);
		$template->add('pageName', Strings::webalize($this->getPageName()));
		$template->add('profile', $profile = $this->getProfile());

		return parent::beforeRender();
	}


    /**
     * @param  string  $name
     * @return TessaControl
     */
    protected function createComponentTessa(string $name): TessaControl
    {
        return new TessaControl($this->bundleManager);
    }
}
