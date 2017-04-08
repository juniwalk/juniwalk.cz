<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Security;

use App\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;

final class Authenticator implements \Nette\Security\IAuthenticator
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var EntityManager
	 */
	private $entityManager;


	/**
	 * @param UserRepository  $userRepository
	 * @param EntityManager  $entityManager
	 */
	public function __construct(UserRepository $userRepository, EntityManager $entityManager)
	{
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
	}


	/**
	 * @param  string[]  $credentials
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials) : IIdentity
	{
		list($username, $password) = $credentials;

		$user = $this->userRepository->findByEmail($username);

		if (!$user || !$user->isPasswordValid($password)) {
			throw new AuthenticationException('app.auth.signIn-failed', $this::INVALID_CREDENTIAL);
		}

		if ($user->isBanned()) {
			throw new AuthenticationException('app.auth.isBanned', $this::NOT_APPROVED);
		}

		if (!$user->isPasswordUpToDate()) {
			$user->setPassword($password);
		}

		$user->setSignIn();
		$this->entityManager->flush();

		return $user;
	}
}
