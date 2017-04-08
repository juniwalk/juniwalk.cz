<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Nette\Security\Passwords;
use Nette\Utils\Strings;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User implements \Nette\Security\IIdentity
{
	use Attributes\Activable;
	use Attributes\Uuid;


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
	private $signUp;

	/**
	 * @ORM\Column(type="datetimetz", nullable=true)
	 * @var \DateTime
	 */
	private $signIn;


	/**
	 * @param string  $email
	 * @param string  $firstName
	 * @param string  $lastName
	 */
	public function __construct(string $email, string $firstName = NULL, string $lastName = NULL)
	{
		$this->createId();
		$this->setEmail($email);
		$this->setLastName($lastName);
		$this->setFirstName($firstName);

		$this->signUp = new DateTime;
	}


	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return Strings::webalize($this->getFullName());
	}


	/**
	 * @param string|NULL  $firstName
	 */
	public function setFirstName(string $firstName = NULL)
	{
		$this->firstName = $firstName ?: NULL;
	}


	/**
	 * @return string|NULL
	 */
	public function getFirstName() : ?string
	{
		return $this->firstName;
	}


	/**
	 * @param string|NULL  $lastName
	 */
	public function setLastName(string $lastName = NULL)
	{
		$this->lastName = $lastName ?: NULL;
	}


	/**
	 * @return string|NULL
	 */
	public function getLastName() : ?string
	{
		return $this->lastName;
	}


	/**
	 * @return string|NULL
	 */
	public function getFullName() : ?string
	{
		return Strings::trim($this->firstName.' '.$this->lastName) ?: NULL;
	}


	/**
	 * @param string  $email
	 */
	public function setEmail(string $email)
	{
		$this->email = Strings::lower($email);
	}


	/**
	 * @return string
	 */
	public function getEmail() : string
	{
		return $this->email;
	}


	/**
	 * @param string|NULL  $password
	 */
	public function setPassword(string $password = NULL)
	{
		if (!empty($password)) {
			$password = Passwords::hash($password);
		}

		$this->password = $password ?: NULL;
	}


	/**
	 * @param  string  $password
	 * @return bool
	 */
	public function isPasswordValid(string $password) : bool
	{
		return Passwords::verify($password, $this->password);
	}


	/**
	 * @return bool
	 */
	public function isPasswordUpToDate() : bool
	{
		return !Passwords::needsRehash($this->password);
	}


	/**
	 * @return string[]
	 */
	public function getRoles() : array
	{
		return [];
	}


	/**
	 * @return \DateTime
	 */
	public function getSignUp() : DateTime
	{
		return clone $this->signUp;
	}


	/**
	 * @param DateTime|NULL  $signIn
	 */
	public function setSignIn(DateTime $signIn = NULL)
	{
		$this->signIn = $signIn ? clone $signIn : new DateTime;
	}


	/**
	 * @return \DateTime|NULL
	 */
	public function getSignIn() : ?DateTime
	{
		if (!$this->signIn) {
			return NULL;
		}

		return clone $this->signIn;
	}
}
