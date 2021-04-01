<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Tracy\Debugger;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Part
{
	use Attributes\Identifier;
	use Attributes\Activable;
	use Attributes\Statusable;
	use Attributes\Timestamp;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="integer", precision=2)
	 * @var float
	 */
	private $price;

	/**
     * @ORM\ManyToOne(targetEntity="Vehicle", inversedBy="parts")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Vehicle
	 */
	private $vehicle;


	/**
	 * @param string  $name
	 * @param int  $price
	 * @param Vehicle  $vehicle
	 */
	public function __construct(string $name, int $price, Vehicle $vehicle)
	{
		$this->parts = new ArrayCollection;
		$this->created = new DateTime;

		$this->name = $name;
		$this->price = $price;
		$this->vehicle = $vehicle;
	}


	/**
	 * @param  string  $name
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * @param  int  $price
	 * @return void
	 */
	public function setPrice(int $price): void
	{
		$this->price = $price;
	}


	/**
	 * @return int
	 */
	public function getPrice(): int
	{
		return $this->price;
	}


	/**
	 * @return Vehicle
	 */
	public function getVehicle(): Vehicle
	{
		return $this->vehicle;
	}


	/**
	 * @ORM\PreUpdate
	 * @param  PreUpdateEventArgs  $e
	 * @return void
	 * @internal
	 */
	public function onPreUpdate(PreUpdateEventArgs $e): void
	{
		Debugger::log(json_encode(['id' => $this->id] + $e->getEntityChangeSet()), 'changes/part');
		$this->setModified(null);
	}
}
