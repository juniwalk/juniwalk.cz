<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\ORM\Proxy\Proxy;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\NoResultException;
use Nette\Application\BadRequestException;

abstract class AbstractRepository
{
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @var string
	 */
	protected $entityName;


	/**
	 * @param  EntityManager  $entityManager
	 * @throws Exception
	 */
	final public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;

		if (!$this->entityName) {
			throw new \Exception;
		}
	}


	/**
	 * @param  int  $id
	 * @return object
	 * @throws BadRequestException
	 */
	public function getById(int $id)
	{
		$builder = $this->createQueryBuilder('e')
			->where('e.id = :id');

		try {
			return $builder->getQuery()
				->setParameter('id', $id)
				->getSingleResult();

		} catch (NoResultException $e) {
			throw new BadRequestException;
		}
	}


	/**
	 * @param  int  $id
	 * @return object|NULL
	 */
	public function findById(int $id)
	{
		try {
			return $this->getById($id);

		} catch (BadRequestException $e) {
			return NULL;
		}
	}


    /**
     * @param  string  $alias
     * @param  string|NULL  $indexBy
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias, string $indexBy = NULL) : QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()->select($alias)
            ->from($this->entityName, $alias, $indexBy);
    }


	/**
	 * @param  int|NULL  $id
	 * @param  string|NULL  $entityName
	 * @return Proxy|Entity|NULL
	 */
	public function getReference(?int $id, string $entityName = NULL)
	{
		if (!$id || empty($id)) {
			return NULL;
		}

		return $this->entityManager->getReference($entityName ?: $this->entityName, $id);
	}
}
