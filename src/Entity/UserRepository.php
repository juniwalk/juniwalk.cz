<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

final class UserRepository extends AbstractRepository
{
	/**
	 * @var string
	 */
	protected $entityName = User::class;


	/**
	 * @param  string  $email
	 * @return User|NULL
	 * @throws BadRequestException
	 */
	public function getByEmail(string $email) : ?User
	{
		$builder = $this->createQueryBuilder('e')
			->where('LOWER(e.email) = LOWER(:email)');

		try {
			return $builder->getQuery()
				->setParameter('email', $email)
				->getSingleResult();

		} catch (NoResultException $e) {
			throw new BadRequestException;
		}
	}
}
