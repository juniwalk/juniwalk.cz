<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

use App\Entity\Enums\Role;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Nette\Security\IIdentity as Identity;
use Nette\Security\Passwords as PasswordManager;
use Nette\Utils\Strings;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User implements Identity
{
	use Attributes\Identifier;
	use Attributes\Activable;

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
     * @ORM\Column(type="string", length=32)
     * @var string
     */
	private $role = Role::USER;

	/**
	 * @ORM\Column(type="datetimetz")
	 * @var DateTime
	 */
	private $signUp;

	/**
	 * @ORM\Column(type="datetimetz", nullable=true)
	 * @var DateTime
	 */
	private $signIn;


	/**
	 * @param string  $email
	 * @param string|null  $firstName
	 * @param string|null  $lastName
	 */
	public function __construct(string $email, string $firstName = null, string $lastName = null)
	{
		$this->setEmail($email);
		$this->setLastName($lastName);
		$this->setFirstName($firstName);

		$this->signUp = new DateTime;
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return Strings::webalize($this->getFullName());
	}


	/**
	 * @param  string|null  $firstName
	 * @return void
	 */
	public function setFirstName(string $firstName = null): void
	{
		$this->firstName = $firstName ?: null;
	}


	/**
	 * @return string|null
	 */
	public function getFirstName(): ?string
	{
		return $this->firstName;
	}


	/**
	 * @param  string|null  $lastName
	 * @return void
	 */
	public function setLastName(string $lastName = null): void
	{
		$this->lastName = $lastName ?: null;
	}


	/**
	 * @return string|null
	 */
	public function getLastName(): ?string
	{
		return $this->lastName;
	}


	/**
	 * @return string|null
	 */
	public function getFullName(): ?string
	{
		return Strings::trim($this->firstName.' '.$this->lastName) ?: null;
	}


	/**
	 * @param  string  $email
	 * @return void
	 */
	public function setEmail(string $email): void
	{
		$this->email = Strings::lower($email);
	}


	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}


	/**
	 * @param  string|null  $password
	 * @param  PasswordManager  $passwordManager
	 * @return void
	 */
	public function setPassword(?string $password, PasswordManager $passwordManager): void
	{
		$this->password = !$password ?: $passwordManager->hash($password);
	}


	/**
	 * @param  string  $password
	 * @param  PasswordManager  $passwordManager
	 * @return bool
	 */
	public function isPasswordValid(string $password, PasswordManager $passwordManager): bool
	{
		return $passwordManager->verify($password, $this->password);
	}


	/**
	 * @param  PasswordManager  $passwordManager
	 * @return bool
	 */
	public function isPasswordUpToDate(PasswordManager $passwordManager): bool
	{
		return !$passwordManager->needsRehash($this->password);
	}


	/**
	 * @param  string  $role
	 * @return void
	 * @throws InvalidEnumException
	 */
	public function setRole(string $role): void
	{
		if (!(new Role)->isValidItem($role)) {
			throw InvalidEnumException::fromItem($role);
		}

		$this->role = $role;
	}


	/**
	 * @return string
	 */
	public function getRole(): string
	{
		return $this->role;
	}


	/**
	 * @return string[]
	 */
	public function getRoles(): array
	{
		return [$this->role];
	}


	/**
	 * @return DateTime
	 */
	public function getSignUp(): DateTime
	{
		return clone $this->signUp;
	}


	/**
	 * @param  DateTime|null  $signIn
	 * @return void
	 */
	public function setSignIn(DateTime $signIn = null): void
	{
		$this->signIn = $signIn ? clone $signIn : new DateTime;
	}


	/**
	 * @return DateTime|null
	 */
	public function getSignIn(): ?DateTime
	{
		if (!$this->signIn) {
			return null;
		}

		return clone $this->signIn;
	}
}
