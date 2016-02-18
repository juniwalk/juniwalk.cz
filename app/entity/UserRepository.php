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
	use \App\Services\Traits\BaseRepository;


	/**
	 * @param  string  $email
	 * @return User|NULL
	 */
	public function getByEmail(string $email)
	{
		return $this->findOneBy(['email' => $email]);
	}
}
