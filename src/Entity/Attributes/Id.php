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

trait Id
{
	/**
	 * @ORM\Column(type="integer", unique=true, nullable=false) @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @var int
	 */
	private $id;


	/**
	 * @return int|NULL
	 */
	final public function getId() : ?int
	{
		return $this->id;
	}


	public function __clone()
	{
		$this->id = NULL;
	}
}
