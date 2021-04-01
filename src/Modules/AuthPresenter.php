<?php declare(strict_types=1);

/**
 * @copyright (c) Martin Procházka
 * @license   MIT License
 */

namespace App\Modules;

use App\Forms\Factory\AuthLockscreenFormFactory;
use App\Forms\Factory\AuthPasswordForgotFormFactory;
use App\Forms\Factory\AuthProfileFormFactory;
use App\Forms\Factory\AuthSignInFormFactory;
use App\Forms\Factory\AuthSignUpFormFactory;
use App\Forms\AuthLockscreenForm;
use App\Forms\AuthPasswordForgotForm;
use App\Forms\AuthProfileForm;
use App\Forms\AuthSignInForm;
use App\Forms\AuthSignUpForm;
use Nette\Security\IUserStorage;

final class AuthPresenter extends AbstractPresenter
{
	/** @var AuthLockscreenFormFactory */
	private $authLockscreenFormFactory;

	/** @var AuthPasswordForgotFormFactory */
	private $authPasswordForgotFormFactory;

	/** @var AuthProfileFormFactory */
	private $authProfileFormFactory;

	/** @var AuthSignInFormFactory */
	private $authSignInFormFactory;

	/** @var AuthSignUpFormFactory */
	private $authSignUpFormFactory;

    /**
	 * @var string
	 * @persistent
	 */
    public $redirect;


	/**
	 * @param AuthLockscreenFormFactory  $authLockscreenFormFactory
	 * @param AuthPasswordForgotFormFactory  $authPasswordForgotFormFactory
	 * @param AuthProfileFormFactory  $authProfileFormFactory
	 * @param AuthSignInFormFactory  $authSignInFormFactory
	 * @param AuthSignUpFormFactory  $authSignUpFormFactory
	 */
	public function __construct(
		AuthLockscreenFormFactory $authLockscreenFormFactory,
		AuthPasswordForgotFormFactory $authPasswordForgotFormFactory,
		AuthProfileFormFactory $authProfileFormFactory,
		AuthSignInFormFactory $authSignInFormFactory,
		AuthSignUpFormFactory $authSignUpFormFactory
	)
	{
		$this->authLockscreenFormFactory = $authLockscreenFormFactory;
		$this->authPasswordForgotFormFactory = $authPasswordForgotFormFactory;
		$this->authProfileFormFactory = $authProfileFormFactory;
		$this->authSignInFormFactory = $authSignInFormFactory;
		$this->authSignUpFormFactory = $authSignUpFormFactory;
	}


	public function actionDefault(): void
	{
		$this->forward('signIn');
	}


	public function actionLockscreen(): void
	{
		$user = $this->getUser();

		if (!$user->isLoggedIn() && $user->getLogoutReason() !== IUserStorage::INACTIVITY) {
			$this->redirect('signIn');
		}
	}


	public function actionSignOut(): void
	{
		$this->user->logout(true);
		$this->redirect('signIn');
	}


	/**
	 * @param  string  $name
	 * @return AuthLockscreenForm
	 */
	protected function createComponentAuthLockscreenForm(string $name): AuthLockscreenForm
	{
		$form = $this->authLockscreenFormFactory->create();
		$form->onSuccess[] = function($form, $data) {
			$this->redirect || $this->redirect('Home:');
			$this->restoreRequest($this->redirect);
		};

		return $form;
	}


	/**
	 * @param  string  $name
	 * @return AuthPasswordForgotForm
	 */
	protected function createComponentAuthPasswordForgotForm(string $name): AuthPasswordForgotForm
	{
		$form = $this->authPasswordForgotFormFactory->create();
		$form->onSuccess[] = function($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}


	/**
	 * @param  string  $name
	 * @return AuthProfileForm
	 */
	protected function createComponentAuthProfileForm(string $name): AuthProfileForm
	{
		$form = $this->authProfileFormFactory->create($this->getProfile());
		$form->onSuccess[] = function($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}


	/**
	 * @param  string  $name
	 * @return AuthSignInForm
	 */
	protected function createComponentAuthSignInForm(string $name): AuthSignInForm
	{
		$form = $this->authSignInFormFactory->create();
		$form->onSuccess[] = function($form, $data) {
			$this->redirect || $this->redirect('Home:');
			$this->restoreRequest($this->redirect);
		};

		return $form;
	}


	/**
	 * @param  string  $name
	 * @return AuthSignUpForm
	 */
	protected function createComponentAuthSignUpForm(string $name): AuthSignUpForm
	{
		$form = $this->authSignUpFormFactory->create();
		$form->onSuccess[] = function($form, $data) {
			$this->getUser()->login($data->email, $data->password);
			$this->redirect('Home:');
		};

		return $form;
	}
}
