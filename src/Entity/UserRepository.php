<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */
namespace App\Entity;

final class UserRepository extends AbstractRepository
{
	/** @var string */
	protected $entityName = User::class;


	/**
	 * @param  string  $email
	 * @return User
	 * @throws BadRequestException
	 */
	public function getByEmail(string $email): User
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


	/**
	 * @param  string  $email
	 * @return User|null
	 */
	public function findByEmail(string $email): ?User
	{
		try {
			return $this->getByEmail($email);

		} catch (BadRequestException $e) {
			return null;
		}
	}
}
