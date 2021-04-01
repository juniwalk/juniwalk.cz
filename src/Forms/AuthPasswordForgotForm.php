<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms;

use App\Entity\UserRepository;
use JuniWalk\Form\AbstractForm;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

final class AuthPasswordForgotForm extends AbstractForm
{
	/** @var UserRepository */
	private $userRepository;


    /**
     * @param UserRepository  $userRepository
     */
    public function __construct(
		UserRepository $userRepository
	) {
		$this->userRepository = $userRepository;

		$this->setTemplateFile(__DIR__.'/templates/authPasswordForgotForm.latte');
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
		$form->addInvisibleReCaptcha('recaptcha')
			->setRequired('nette.user.captcha-required');

        $form->addSubmit('submit');

		return $form;
	}


    /**
     * @param Form  $form
     * @param ArrayHash   $data
     */
    protected function handleSuccess(Form $form, ArrayHash $data): void
    {
    	try {
			$user = $this->userRepository->getByEmail($data->email);

		} catch (BadRequestException $e) {
			$form['email']->addError('nette.message.auth-email-unknown');
		}
    }
}
