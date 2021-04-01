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
class Image
{
	use Attributes\Identifier;
	use Attributes\Timestamp;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
     * @ORM\ManyToOne(targetEntity="Vehicle", inversedBy="images")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Vehicle
	 */
	private $vehicle;


	/**
	 * @param string  $name
	 * @param Vehicle  $vehicle
	 */
	public function __construct(string $name, Vehicle $vehicle)
	{
		$this->created = new DateTime;

		$this->name = $name;
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
		Debugger::log(json_encode(['id' => $this->id] + $e->getEntityChangeSet()), 'changes/image');
		$this->setModified(null);
	}
}
