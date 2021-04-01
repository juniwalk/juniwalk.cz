<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait Activable
{
	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	private $isActive = true;


	/**
	 * @param  bool  $active
	 * @return void
	 */
	public function setActive(bool $active): void
	{
		$this->isActive = $active;
	}


	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->isActive;
	}
}
