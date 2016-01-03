<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

final class UserRepository extends \Kdyby\Doctrine\EntityRepository
{
	/**
	 * Get the instance of the User by email.
	 * @param  string  $email
	 * @return User
	 */
	public function getByEmail(string $email) : User
	{
		return $this->findOneBy(['email' => $email]);
	}
}
