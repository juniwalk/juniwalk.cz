<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
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
	private $isActive = TRUE;


	/**
	 * @param bool  $active
	 */
	public function setActive(bool $active)
	{
		$this->isActive = $active;
	}


	/**
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->isActive;
	}
}
