<?php declare(strict_types=1);

/**
 * @copyright (c) Martin Procházka
 * @license   MIT License
 */

namespace App\Security;

use App\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity as Identity;
use Nette\Security\Passwords as PasswordManager;

final class Authenticator implements IAuthenticator
{
	/** @var PasswordManager */
	private $passwordManager;

	/** @var UserRepository */
	private $userRepository;

	/** @var EntityManager */
	private $entityManager;


	/**
	 * @param EntityManager  $entityManager
	 * @param UserRepository  $userRepository
	 * @param PasswordManager  $passwordManager
	 */
	public function __construct(
		EntityManager $entityManager,
		UserRepository $userRepository,
		PasswordManager $passwordManager
	) {
		$this->passwordManager = $passwordManager;
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
	}


	/**
	 * @param  string[]  $credentials
	 * @return Identity
	 * @throws AuthenticationException
	 */
	public function authenticate(iterable $credentials): Identity
	{
		list($username, $password) = $credentials;

		$user = $this->userRepository->getByEmail($username);
		$user->setSignIn(null);

		if (!$user->isPasswordValid($password, $this->passwordManager)) {
			throw new AuthenticationException('nette.message.auth-invalid', $this::INVALID_CREDENTIAL);
		}

		if (!$user->isActive()) {
			throw new AuthenticationException('nette.message.auth-banned', $this::NOT_APPROVED);
		}

		if (!$user->isPasswordUpToDate($this->passwordManager)) {
			$user->setPassword($password);
		}

		$this->entityManager->flush();

		return $user;
	}
}
