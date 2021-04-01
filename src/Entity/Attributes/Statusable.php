<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Attributes;

use App\Entity\Enums\Status;
use App\Exceptions\InvalidEnumException;
use Doctrine\ORM\Mapping as ORM;

trait Statusable
{
	/**
	 * @ORM\Column(type="string", length=16)
	 * @var bool
	 */
	private $status = Status::CREATED;


	/**
	 * @param  string  $status
	 * @return void
	 * @throws InvalidEnumException
	 */
	public function setStatus(string $status): void
	{
		if (!(new Status)->isValidItem($status)) {
			throw InvalidEnumException::fromItem($status);
		}

		$this->status = $status;
	}


    /**
     * @param  string  $status
     * @return bool
     */
    public function isStatus(string $status): bool
    {
        return $this->status === $status;
    }


	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}
}
