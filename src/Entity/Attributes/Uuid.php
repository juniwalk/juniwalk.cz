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
use Ramsey\Uuid\Uuid as Id;

trait Uuid
{
	/**
	 * @ORM\Column(type="uuid", unique=true) @ORM\Id
	 * @var Id
	 */
	private $id;


	/**
	 * Create new UUID when entity gets cloned.
	 */
	public function __clone()
	{
		$this->createId();
	}


	/**
	 * @return Id
	 */
	final public function getId() : Id
	{
		return $this->id;
	}


	final private function createId()
	{
		$this->id = Id::uuid4();
	}
}
