<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Security;

use App\Entity\User;
use App\Entity\UserRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Ramsey\Uuid\Uuid;

final class AccessManager
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var Cache
	 */
	private $cache;


	/**
	 * @param IStorage  $storage
	 * @param UserRepository  $userRepository
	 */
	public function __construct(IStorage $storage, UserRepository $userRepository)
	{
		$this->cache = new Cache($storage, 'AccessManager.Tokens');
		$this->userRepository = $userRepository;
	}


	/**
	 * @param  User  $user
	 * @param  string|NULL  $slug
	 * @param  string[]  $cache
	 * @return string
	 * @throws Exception
	 */
	public function createToken(User $user, string $slug = NULL, array $cache = []) : string
	{
		$token = (string) Uuid::uuid4();

		if (!$user->getId()) {
			throw new \Exception;
		}

		$this->cache->save($token, ['slug' => $slug, 'user' => $user->getId()], $cache);
		return $token;
	}


	/**
	 * @param  Presenter  $presenter
	 * @param  string  $token
	 * @param  bool  $onLoadRemove
	 * @return User
	 * @throws BadRequestException
	 */
	public function validateToken(Presenter $presenter, string $token, bool $onLoadRemove = TRUE) : User
	{
		$slug = $presenter->getName().':'.$presenter->getAction();

		if (!Uuid::isValid($token) || !$value = $this->cache->load($token)) {
			throw new BadRequestException('Access token has expired', 403);
		}

		if ($value['slug'] && $value['slug'] !== $slug) {
			throw new BadRequestException('Slug missmatch, expected '.$value['slug'].' but '.$slug.' was given', 403);
		}

		if ($onLoadRemove) {
			$this->clearToken($token);
		}

		return $this->userRepository->getReference($value['user']);
	}


	/**
	 * @param string  $token
	 */
	public function clearToken(string $token)
	{
		$this->cache->remove($token);
	}
}
