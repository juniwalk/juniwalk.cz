<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

use Nette\Utils\Html;

final class AuthPresenter extends \Nette\Application\UI\Presenter
{
	/** @var \JuniWalk\Forms\FormLocator @inject */
	public $formLocator;


	/**
	 * Action - Default action for authentication.
	 */
	public function actionDefault()
	{
		$this->redirect('signIn');
	}


	/**
	 * Action - Sign out from the application.
	 */
	public function actionSignOut()
	{
		$this->user->logout(true);
		$this->redirect('signIn');
	}


	/**
	 * Create new instance of the Form component.
	 * @param  string  $name  Component name
	 * @return Nette\Forms\Form
	 */
	protected function createComponentSignInForm(string $name)
	{
		$form = $this->formLocator->find('signIn');
		$form->onSuccess[] = function($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}


	/**
	 * Create new instance of the Form component.
	 * @param  string  $name  Component name
	 * @return Nette\Forms\Form
	 */
	protected function createComponentSignUpForm(string $name)
	{
		$form = $this->formLocator->find('signUp');
		$form->onSuccess[] = function($form, $data) {
			$this->redirect('Home:');
		};

		return $form;
	}
}
