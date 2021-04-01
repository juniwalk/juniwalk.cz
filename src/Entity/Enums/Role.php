<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Enums;

use App\Exceptions\InvalidEnumException;
use Nette\Utils\Html;

final class Role extends AbstractEnum
{
	/** @var string */
	const GUEST = 'guest';
	const USER = 'user';
	const MANAGER = 'manager';
	const ADMIN = 'admin';

	/** @var string[] */
	protected $items = [
		//self::GUEST => 'enum.role.guest',
		self::USER => 'enum.role.user',
		self::MANAGER => 'enum.role.manager',
		self::ADMIN => 'enum.role.admin',
	];

	/** @var string[] */
	private $colors = [
		self::GUEST => 'default',
		self::USER => 'success',
		self::MANAGER => 'primary',
		self::ADMIN => 'warning',
	];

	/** @var int[] */
	private $weight = [
		self::GUEST => 3,
		self::USER => 2,
		self::MANAGER => 1,
		self::ADMIN => 0,
	];

	/** @var string[] */
	private $map = [
		self::GUEST => null,
		self::USER => self::GUEST,
		self::MANAGER => self::USER,
		self::ADMIN => null,
	];


	/**
	 * @param  string  $role
	 * @param  string  $slave
	 * @return bool
	 */
	public function canChangeRole(string $role, string $slave): bool
	{
		if (!$this->isValidItem($role) || !$this->isValidItem($slave)) {
			throw InvalidEnumException::fromItem($role.'/'.$slave);
		}

		return $this->weight[$role] <= $this->weight[$slave];
	}


	/**
	 * @param  string  $item
	 * @return string
	 * @throws InvalidEnumException
	 */
	public function getColor(string $item): string
	{
		if (!$this->isValidItem($item)) {
			throw InvalidEnumException::fromItem($item);
		}

		return $this->colors[$item];
	}


	/**
	 * @return string[]
	 */
	public function getMap(): iterable
	{
		return $this->map;
	}
}
