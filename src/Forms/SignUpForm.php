<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

final class SignUpForm extends \JuniWalk\Form\AbstractForm
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;


	/**
	* @param EntityManager  $entityManager
	*/
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;

		$this->setTemplateFile(__DIR__.'/templates/signUpForm.latte');
	}


	/**
	* @param  string  $name
	* @return Form
	*/
	protected function createComponentForm(string $name) : Form
	{
		$form = parent::createComponentForm($name);
		$form->addText('name')->setRequired('client.auth.login-required');
		$form->addText('email')->setRequired('client.auth.login-required')
			->addRule($form::EMAIL, 'client.auth.login-invalid');
		$form->addPassword('password')->setRequired('client.auth.password-required')
			->addRule($form::MIN_LENGTH, 'client.auth.password-length', 6);
		$form->addCheckbox('agreement');

		$form->addSubmit('submit');

		return $form;
	}


    /**
     * @param Form  $form
     * @param ArrayHash  $data
     */
    protected function handleSuccess(Form $form, ArrayHash $data)
    {
		$user = new User($data->email, ... explode(' ', $data->name));
		$user->setPassword($data->password);

		try {
			$this->entityManager->persist($user);
			$this->entityManager->flush($user);

		} catch (UniqueConstraintViolationException $e) {
			return $form['email']->addError('user.auth.email-taken');
		}
	}
}
