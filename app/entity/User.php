<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User implements \Nette\Security\IIdentity
{
	/**
	 * Use Entity attributes from Kdyby\Doctrine
	 */
	use \Kdyby\Doctrine\Entities\Attributes\UniversallyUniqueIdentifier;
	use \Kdyby\Doctrine\Entities\MagicAccessors;


	/**
	 * @ORM\Column(type="string", length=16, nullable=true)
	 * @var string
	 */
	private $firstName;

	/**
	 * @ORM\Column(type="string", length=48, nullable=true)
	 * @var string
	 */
	private $lastName;

	/**
	 * @ORM\Column(type="string", unique=true)
	 * @var string
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	private $signUp = 'NOW';

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime
	 */
	private $signIn;


	/**
	 * Collect entity dependencies.
	 * @param string  $email   User email
	 */
	public function __construct($email)
	{
		$this->signUp = new \DateTime($this->signUp);
		$this->email = Strings::lower($email);
	}


	/**
	 * Get assigned roles of the User.
	 * @return array
	 */
	public function getRoles()
	{
		return [];
	}
}
