<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use JuniWalk\Form\AbstractForm;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\Utils\ArrayHash;

final class AuthSignInForm extends AbstractForm
{
	/** @var User */
	private $user;


	/**
	 * @param User  $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;

		$this->setTemplateFile(__DIR__.'/templates/authSignInForm.latte');
	}


	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}


	/**
	 * @param  string  $name
	 * @return Form
	 */
	protected function createComponentForm(string $name): Form
	{
		$form = parent::createComponentForm($name);
        $form->addText('email')->setRequired('nette.user.email-required')
            ->addRule($form::EMAIL, 'nette.user.email-invalid');
        $form->addPassword('password')->setRequired('nette.user.password-required')
            ->addRule($form::MIN_LENGTH, 'nette.user.password-length', 6);
        $form->addCheckbox('remember');

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
        $user->setExpiration('1 month');

        if (!$data->remember) {
            $user->setExpiration('1 hour');
            $user->setExpiration('5 minutes');
        }

        try {
            $user->login($data->email, $data->password);

        } catch (BadRequestException $e) {
            $form->addError('nette.message.auth-invalid');

        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }
}
