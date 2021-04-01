<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Enums;

use App\Exceptions\InvalidEnumException;
use Nette\Utils\Html;

final class Status extends AbstractEnum
{
	/** @var string */
    const UNKNOWN = 'unknown';
	const CREATED = 'available';
	const AVAILABLE = 'available';
	const RESERVED = 'reserved';
    const SOLD = 'sold';
    const KEPT = 'kept';

	/** @var string[] */
	protected $items = [
		self::UNKNOWN => 'enum.status.unknown',
		self::AVAILABLE => 'enum.status.available',
		self::RESERVED => 'enum.status.reserved',
		self::SOLD => 'enum.status.sold',
		self::KEPT => 'enum.status.kept',
	];

	/** @var string[] */
	private $colors = [
		self::UNKNOWN => 'warning',
		self::AVAILABLE => 'success',
		self::RESERVED => 'info',
		self::SOLD => 'primary',
		self::KEPT => 'default',
	];


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
}
