<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use App\Entity\Project;
use App\Entity\User;
use App\Models\MessageManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\ORM\ORMException;
use Nette\Application\UI\Form;
use Nette\Security\Passwords as PasswordManager;
use Nette\Utils\ArrayHash;
use JuniWalk\Form\AbstractForm;

final class AuthSignUpForm extends AbstractForm
{
	/** @var PasswordManager */
	private $passwordManager;

	/** @var EntityManager */
	private $entityManager;


	/**
	* @param EntityManager  $entityManager
	* @param PasswordManager  $passwordManager
	*/
	public function __construct(
		EntityManager $entityManager,
		PasswordManager $passwordManager
	) {
		$this->passwordManager = $passwordManager;
		$this->entityManager = $entityManager;

		$this->setTemplateFile(__DIR__.'/templates/authSignUpForm.latte');
	}


	/**
	* @param  string  $name
	* @return Form
	*/
	protected function createComponentForm(string $name): Form
	{
		$form = parent::createComponentForm($name);
		$form->addText('name')->setRequired('client.auth.login-required');
		$form->addText('email')->setRequired('client.auth.login-required')
			->addRule($form::EMAIL, 'client.auth.login-invalid');
		$form->addPassword('password')->setRequired('client.auth.password-required')
			->addRule($form::MIN_LENGTH, 'client.auth.password-length', 6);
		$form->addCheckbox('agreement');
		$form->addInvisibleReCaptcha('recaptcha')
			->setRequired('nette.user.captcha-required');

		$form->addSubmit('submit');

		return $form;
	}


    /**
     * @param  Form  $form
     * @param  ArrayHash  $data
	 * @return void
     */
    protected function handleSuccess(Form $form, ArrayHash $data): void
    {
		$user = new User($data->email, ... explode(' ', $data->name));
		$user->setPassword($data->password, $this->passwordManager);

		try {
			$this->entityManager->persist($user);
			$this->entityManager->flush();

		} catch (UniqueConstraintViolationException $e) {
			$form['email']->addError('user.auth.email-taken');
		}
	}
}
