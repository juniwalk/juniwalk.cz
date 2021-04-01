<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Enums;

use App\Exceptions\InvalidEnumException;

abstract class AbstractEnum
{
	/** @var string[] */
	protected $items = [];


	/**
	 * @param  mixed  $item
	 * @return string
	 * @throws InvalidEnumException
	 */
	public function getItem($item): string
	{
		if (!$this->isValidItem($item)) {
			throw InvalidEnumException::fromItem($item);
		}

		return $this->items[$item];
	}


	/**
	 * @return string[]
	 */
	public function getItems(): iterable
	{
		return $this->items;
	}


	/**
	 * @param  mixed  $value
	 * @return bool
	 */
	public function isValidItem($value): bool
	{
		return isset($this->items[$value]);
	}
}
