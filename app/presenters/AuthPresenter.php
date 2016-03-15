<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

final class AuthPresenter extends \Nette\Application\UI\Presenter
{
	use \App\Services\Traits\BasePresenter;


	/** @var \App\Forms\ISignInFormFactory @inject */
	public $signInForm;

	/** @var \App\Forms\ISignUpFormFactory @inject */
	public $signUpForm;


	public function actionDefault()
	{
		$this->redirectAjax('signIn');
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
		$form = $this->signInForm->create();
		$form->onSuccess[] = function ($form, $data) {
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
		$form = $this->signUpForm->create();
		$form->onSuccess[] = function ($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}
}
