<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Services\Traits;

use Nette\Application\BadRequestException;

trait BaseRepository
{
	/**
	 * @param  int  $id
	 * @return object
	 * @throws BadRequestException
	 */
	public function getById($id)
	{
		if (!$entity = $this->findOneBy(['id' => $id])) {
			throw new BadRequestException;
		}

		return $entity;
	}


	/**
	 * @param  int  $id
	 * @return object|NULL
	 */
	public function findById($id)
	{
		return $this->findOneBy(['id' => $id]);
	}


	/**
	 * @param  integer  $id
	 * @param  string   $entityName
	 * @return \Doctrine\ORM\Proxy\Proxy
	 */
	public function getReference($id, $entityName = NULL)
	{
		return $this->_em->getReference($entityName ?: $this->_entityName, $id);
	}


	/**
	 * @return Doctrine\ORM\Query\Expr
	 */
	public function expr()
	{
		return new \Doctrine\ORM\Query\Expr;
	}
}
