<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Attributes;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $created;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var DateTime|null
	 */
	private $modified;


	/**
	 * @return DateTime
	 */
	public function getCreated(): DateTime
	{
		return clone $this->created;
	}


	/**
	 * @param DateTime|null  $modified
	 * @return void
	 */
	public function setModified(?DateTime $modified): void
	{
		$this->modified = $modified ? clone $modified : new DateTime;
	}


	/**
	 * @return DateTime|null
	 */
	public function getModified(): ?DateTime
	{
		if (!$this->modified) {
			return null;
		}

		return clone $this->modified;
	}
}
