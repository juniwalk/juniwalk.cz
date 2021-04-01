<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use App\Entity\User;
use Carrooi\ImagesManager\ImagesManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use JuniWalk\Form\AbstractForm;

final class AuthProfileForm extends AbstractForm
{
	/** @var EntityManager */
	private $entityManager;

	/** @var User */
	private $user;


	/**
	 * @param User  $user
	 * @param UserManager  $userManager
	 * @param EntityManager  $entityManager
	 */
	public function __construct(
		User $user,
		EntityManager $entityManager
	) {
		$this->entityManager = $entityManager;
		$this->user = $user;

		$this->setTemplateFile(__DIR__.'/templates/authProfileForm.latte');
		$this->onBeforeRender[] = function($form, $template) {
			$template->add('profile', $this->user);
			$this->setDefaults($this->user);
		};
	}


	/**
	 * @return void
	 * @secured
	 */
	public function handleRemoveImage(): void
	{
	}


	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}


	/**
	 * @param  User|null  $user
	 * @return void
	 */
	public function setDefaults(?User $user): void
	{
		$form = $this->getForm();

		if (!isset($user)) {
			return;
		}

		$form->setDefaults([
			'name' => $user->getFullName(),
			'email' => $user->getEmail(),
		]);
	}


	/**
	 * @param  string  $name
	 * @return Form
	 */
	protected function createComponentForm(string $name): Form
	{
		$form = parent::createComponentForm($name);
		$form->addUpload('image')->addCondition($form::FILLED)
			->addRule($form::MAX_FILE_SIZE, 'nette.user.image-too-large', 2097152)
			->addRule($form::IMAGE, 'nette.user.image-invalid');
		$form->addText('name')->setRequired('nette.user.name-required');
		$form->addText('email')->setRequired('nette.user.email-required')->setType('email');
		$form->addPassword('password')->addCondition($form::FILLED)
			->addRule($form::MIN_LENGTH, 'nette.user.password-length', 6);

        $form->addSubmit('submit');

		return $form;
	}


    /**
     * @param Form  $form
     * @param ArrayHash  $data
     */
    protected function handleSuccess(Form $form, ArrayHash $data): void
    {
    	$user = $this->getUser();
		$user->setName($data->name);
		$user->setEmail($data->email);

		if (!empty($data->password)) {
			$user->setPassword($data->password);
		}

		try {
			$this->entityManager->persist($user);
			$this->entityManager->flush();

		} catch(UniqueConstraintViolationException $e) {
			$form->addError('email', 'nette.message.email-taken');
		}
    }
}
