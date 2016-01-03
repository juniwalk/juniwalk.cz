<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Services;

use App\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Nette\Security\AuthenticationException;

final class Authenticator implements \Nette\Security\IAuthenticator
{
	/** @var UserRepository */
	private $userRepository;

	/** @var EntityManager */
	private $entityManager;


	/**
	 * @param UserRepository  $userRepository
	 * @param EntityManager   $entityManager
	 */
	public function __construct(UserRepository $userRepository, EntityManager $entityManager)
	{
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
	}


	/**
	 * Authenticate User's signin request.
	 * @param  string[]  $login
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $login) : \Nette\Security\IIdentity
	{
		$user = $this->userRepository->getbyEmail($login[0]);

		if (!$user || !$user->verifyPassword($login[1])) {
			throw new AuthenticationException('user.auth.signInFailed');
		}

		if ($user->isBanned()) {
			throw new AuthenticationException('user.auth.isBanned');
		}

		$this->entityManager->flush($user->signedIn());

		return $user;
	}
}
