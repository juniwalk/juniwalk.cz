<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Modules;

use App\Forms\Factory\SignInFormFactory;
use App\Forms\Factory\SignUpFormFactory;

final class AuthPresenter extends AbstractPresenter
{
	/**
	 * @var SignInFormFactory
	 */
	public $signInFormFactory;

	/**
	 * @var SignUpFormFactory
	 */
	public $signUpFormFactory;


	/**
	 * @param SignInFormFactory  $signInFormFactory
	 * @param SignUpFormFactory  $signUpFormFactory
	 */
	public function __construct()
	{
		$this->signInFormFactory = $signInFormFactory;
		$this->signUpFormFactory = $signUpFormFactory;
	}


	public function actionDefault()
	{
		$this->forward('signIn');
	}


	public function actionSignOut()
	{
		$this->user->logout(TRUE);
		$this->redirect('signIn');
	}


	/**
	 * @param  string  $name
	 * @return Nette\Forms\Form
	 */
	protected function createComponentSignInForm(string $name)
	{
		$form = $this->signInFormFactory->create();
		$form->onSuccess[] = function($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}


	/**
	 * @param  string  $name
	 * @return Nette\Forms\Form
	 */
	protected function createComponentSignUpForm(string $name)
	{
		$form = $this->signUpFormFactory->create();
		$form->onSuccess[] = function ($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}
}
