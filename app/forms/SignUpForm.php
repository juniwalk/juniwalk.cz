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

final class SignUpForm extends \JuniWalk\Forms\FormControl
{
	/** @var EntityManager */
	private $entityManager;


    /**
     * @param EntityManager  $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
		$this->setTemplateFile(__DIR__.'/templates/signUpForm.latte');
		$this->entityManager = $entityManager;
    }


	/**
	 * @param  string  $name
	 * @return Form
	 */
	protected function createComponentForm($name)
	{
		$form = parent::createComponentForm($name);
        $form->addText('name')->setRequired('client.auth.login-required')
			->setAttribute('autofocus');
        $form->addText('email')->setRequired('client.auth.login-required')
            ->addRule($form::EMAIL, 'client.auth.login-invalid');
        $form->addPassword('password')->setRequired('client.auth.password-required')
            ->addRule($form::MIN_LENGTH, 'client.auth.password-length', 6);
        $form->addCheckbox('agreement');
        $form->addSubmit('submit');

		return $form;
	}


    /**
     * @param static  $form  Form instance
     * @param mixed   $data  Submited data
     */
    protected function handleSuccess($form, $data)
    {
    	$user = new User($data->email, ... explode(' ', $data->name));
		$user->changePassword($data->password);

		try {
			$this->entityManager->persist($user)->flush($user);

		} catch (UniqueConstraintViolationException $e) {
			return $form['email']->addError('user.auth.email-taken');

		} catch (\Exception $e) {
			return $form->addError('app.general.error');
		}
    }
}

interface ISignUpFormFactory
{
    /**
     * @return SignUpForm
     */
    public function create() : SignUpForm;
}
