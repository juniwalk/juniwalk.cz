<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Attributes;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait Ownership
{
	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(nullable=false)
	 * @var User
	 */
	private $user;


	/**
	 * @param  User  $user
	 * @return void
	 */
	public function setUser(User $user): void
	{
		$this->user = $user;
	}


	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}


	/**
	 * @param  User  $user
	 * @return bool
	 */
	public function isOwner(User $user): bool
	{
		return $this->user->getId() === $user->getId();
	}
}
