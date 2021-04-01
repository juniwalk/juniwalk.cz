<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait Identifier
{
	/**
	 * @ORM\Column(type="integer", unique=true, nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Id
	 * @var int
	 */
	private $id;


	public function __clone()
	{
		$this->id = null;
	}


	/**
	 * @return int|null
	 */
	final public function getId(): ?int
	{
		return $this->id;
	}


	/**
	 * @param  self|null  $class
	 * @return bool
	 */
	final public function isCopyOf(self $class): bool
	{
		return $this->id == $class->getId();
	}
}
