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
use Nette\Security\Passwords;
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
	use \Kdyby\Doctrine\Entities\MagicAccessors;


	/**
	 * @ORM\Column(type="guid") @ORM\Id
	 * @var string
	 */
	private $id;

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
		$this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
		$this->signUp = new \DateTime($this->signUp);
		$this->email = Strings::lower($email);
	}


	/**
	 * Get the Id of the User.
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get assigned roles of the User.
	 * @return array
	 */
	public function getRoles()
	{
		return [];
	}


	/**
	 * Change the name of the User.
	 * @param  string  $firstName
	 * @param  string  $lastName
	 * @return static
	 */
	public function changeName($firstName, $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		return $this;
	}


	/**
	 * Change the password of this User.
	 * @param  string  $value  New password
	 * @return static
	 */
	public function changePassword($value)
	{
		$this->password = $value ? Passwords::hash($value) : null;
		return $this;
	}


	/**
	 * Verify given password.
	 * @param  string  $value  Password to verify
	 * @return bool
	 */
	public function verifyPassword($value)
	{
		return Passwords::verify($value, $this->password);
	}
}
