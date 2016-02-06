<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use Nette\Security\AuthenticationException;
use Nette\Security\User;

final class SignInForm extends \JuniWalk\Form\FormControl
{
    /** @var User */
    private $user;


    /**
     * @param User  $user
     */
    public function __construct(User $user)
    {
		$this->setTemplateFile(__DIR__.'/templates/signInForm.latte');
        $this->user = $user;
    }


	/**
	 * @return User
	 */
	public function getUser() : User
	{
		return $this->user;
	}


	/**
	 * @param  string  $name
	 * @return Form
	 */
	protected function createComponentForm($name)
	{
		$form = $this->createForm($name);
        $form->addText('email')->setRequired('client.auth.login-required')
            ->addRule($form::EMAIL, 'client.auth.login-invalid');
        $form->addPassword('password')->setRequired('client.auth.password-required')
            ->addRule($form::MIN_LENGTH, 'client.auth.password-length', 6);
        $form->addCheckbox('remember');
        $form->addSubmit('submit');

		return $form;
	}


    /**
     * @param static  $form
     * @param mixed   $data
     */
    protected function handleSuccess($form, $data)
    {
    	$user = $this->getUser();
        $user->setExpiration('2 weeks');

        if (!$data->remember) {
            $user->setExpiration('1 hour', $user::BROWSER_CLOSED);
        }

        try {
            $user->login($data->email, $data->password);

        } catch (AuthenticationException $e) {
            return $form->addError($e->getMessage());

        } catch (\Exception $e) {
			return $form->addError('app.general.error');
        }
    }
}

interface ISignInFormFactory
{
    /**
     * @return SignInForm
     */
    public function create() : SignInForm;
}
