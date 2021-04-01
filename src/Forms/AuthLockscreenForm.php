<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use Nette\Security\User;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use JuniWalk\Form\AbstractForm;

final class AuthLockscreenForm extends AbstractForm
{
	/** @var User */
	private $user;


	/**
	 * @param User  $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;

		$this->setTemplateFile(__DIR__.'/templates/authLockscreenForm.latte');
		$this->onBeforeRender[] = function($form, $template) use ($user) {
			$template->add('profile', $user->getIdentity());
		};
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
        $form->addPassword('password')->setRequired('nette.user.password-required')
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
		$identity = $user->getIdentity();

        try {
            $user->login($identity->getEmail(), $data->password);

        } catch (BadRequestException $e) {
            $form->addError('nette.message.auth-invalid');

        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }
}
