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
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Entity\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements \Nette\Security\IIdentity
{
	/**
	 * @ORM\Column(type="uuid", unique=true) @ORM\Id
	 * @var Uuid
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
	 * @ORM\Column(type="datetimetz")
	 * @var \DateTime
	 */
	private $signUp = 'NOW';

	/**
	 * @ORM\Column(type="datetimetz", nullable=true)
	 * @var \DateTime
	 */
	private $signIn;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	private $isBanned = FALSE;


	/**
	 * @param string  $email
	 * @param string  $firstName
	 * @param string  $lastName
	 */
	public function __construct(string $email, string $firstName = NULL, string $lastName = NULL)
	{
		$this->signUp = new \DateTime($this->signUp);
		$this->id = Uuid::uuid4();

		$this->rename($firstName, $lastName);
		$this->changeEmail($email);
	}


	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return Strings::webalize($this->firstName.' '.$this->lastName);
	}


	/**
	 * Make sure UUID is still unique.
	 */
	public function __clone()
	{
		$this->id = Uuid::uuid4();
	}


	/**
	 * @return Uuid
	 */
	final public function getId() : Uuid
	{
		return $this->id;
	}


	/**
	 * @return string[]
	 */
	public function getRoles() : array
	{
		return [];
	}


	/**
	 * @return bool
	 */
	public function isBanned() : bool
	{
		return $this->isBanned;
	}


	/**
	 * Set signIn time to current timestamp.
	 * @return static
	 */
	public function signedIn() : self
	{
		$this->signIn = new \DateTime;
		return $this;
	}


	/**
	 * @param string  $firstName
	 * @param string  $lastName
	 */
	public function rename(string $firstName = NULL, string $lastName = NULL)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}


	/**
	 * @param string  $value
	 */
	public function changeEmail(string $value)
	{
		$this->email = Strings::lower($value);
	}


	/**
	 * @param string  $value
	 */
	public function changePassword(string $value)
	{
		$this->password = $value ? Passwords::hash($value) : NULL;
	}


	/**
	 * @param  string  $value
	 * @return bool
	 */
	public function verifyPassword(string $value) : bool
	{
		return Passwords::verify($value, $this->password);
	}
}
