<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

final class HomePresenter extends \Nette\Application\UI\Presenter
{
	/** @var \JuniWalk\Forms\FormLocator @inject */
	public $formLocator;


	/**
	 * Create new instance of the Form component.
	 * @param  string  $name  Component name
	 * @return Nette\Forms\Form
	 */
	protected function createComponentSignInForm(string $name)
	{
		$form = $this->formLocator->find('signIn');
		$form->onSuccess[] = function($form, $data) {
			$this->redirect('default');
		};

		return $form;
	}
}
