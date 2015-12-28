<?php

/**
 * @author    Design Point <info@dpoint.cz>
 * @package   PT Project
 * @link      https://app.ptproject.cz
 * @copyright Design Point (c) 2015
 * @license   Proprietary
 */

namespace App\Forms;

use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\ReCaptchaHolder;
use Nette\Http\Session;
use Nette\Forms\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

final class SignInForm extends \JuniWalk\Forms\FormControl
{
    /** @var User */
    private $user;

    /** @var Nette\Http\SessionSection */
    private $session;


    /**
     * @param User     $user     User instance
     * @param Session  $session  Session provider
     */
    public function __construct(User $user, Session $session)
    {
        //$this->session = $session->getSection('signInAttempt')->setExpiration('20 minutes');
        $this->user = $user;
        parent::__construct();
    }


	/**
	 * @param  string  $name
	 * @return Form
	 */
	protected function createComponentForm($name)
	{
		$form = parent::createComponentForm($name);

        // Email is needed for the signin process
        $form->addText('email', 'client.auth.login')
            ->setType('email')->setAttribute('autofocus')
            ->setRequired('client.auth.login-required')
            ->addRule($form::EMAIL, 'client.auth.login-invalid');

        // We can't authenticate without the password
        $form->addPassword('password', 'client.auth.password')
            ->setRequired('client.auth.password-required')
            ->addRule($form::MIN_LENGTH, 'client.auth.password-length', 6);

        // If there was more than 5 attempts
        //if ($this->session->attempt > 4) {
		//	$this->addReCaptcha($form, 'captcha', 'client.auth.captcha')
        //        ->setRequired('client.auth.captcha-required');
        //}

        // Optional - Allow extended session lifetime
        $form->addCheckbox('remember', 'client.auth.rememberMe');

        $form->addSubmit('submit', 'client.auth.signIn');

		return $form;
	}


    /**
     * @param static  $form  Form instance
     * @param mixed   $data  Submited data
     */
    protected function handleSuccess($form, $data)
    {
        $user = $this->user->setExpiration('14 days');

        // We shall not remember
        if (!$data->remember) {
            $user->setExpiration('1 hour', $user::BROWSER_CLOSED);
        }

        try {
        //    $this->session->attempt += 1;
            $user->login($data->email, $data->password);

        } catch (AuthenticationException $e) {
            return $this->addError($form, $e->getMessage());

        } catch (\Exception $e) {
        	return $this->addError($form, 'client.general.error');
        }

        // Clear all previous attempts
        //unset($this->session->attempt);
    }


    /**
     * @param  Form    $form   Form instance
     * @param  string  $name   Field name
     * @param  string  $label  Html label
     * @return ReCaptchaField
     */
    public function addReCaptcha(Form $form, string $name, string $label = NULL) : ReCaptchaField
    {
        return $form[$name] = new ReCaptchaField(ReCaptchaHolder::getSiteKey(), $label);
    }
}

interface ISignInFormFactory
{
    /**
     * @return SignInForm
     */
    public function create() : SignInForm;
}
