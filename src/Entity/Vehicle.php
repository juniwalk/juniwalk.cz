<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Tracy\Debugger;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Vehicle
{
	use Attributes\Identifier;
	use Attributes\Activable;
	use Attributes\Ownership;
	use Attributes\Timestamp;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=7, nullable=true)
	 * @var string
	 */
	private $vin;

	/**
	 * @ORM\Column(type="integer", precision=2)
	 * @var float
	 */
	private $price;

	/**
	 * @ORM\Column(type="integer", precision=2)
	 * @var float
	 */
	private $priceTow = 0;

	/**
	 * @ORM\Column(type="integer", precision=2)
	 * @var float
	 */
	private $priceRecall = 0;

	/**
	 * @ORM\OneToMany(targetEntity="Part", mappedBy="vehicle", indexBy="id")
	 * @var Part[]
	 */
	private $parts;

	/**
	 * @ORM\OneToMany(targetEntity="Image", mappedBy="vehicle", indexBy="id")
	 * @var Image[]
	 */
	private $images;


	/**
	 * @param string  $name
	 * @param int  $price
	 * @param User  $user
	 */
	public function __construct(string $name, int $price, User $user)
	{
		$this->images = new ArrayCollection;
		$this->parts = new ArrayCollection;
		$this->created = new DateTime;

		$this->name = $name;
		$this->price = $price;
		$this->user = $user;
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
	 * @param  string|null  $vin
	 * @return void
	 */
	public function setVin(?string $vin): void
	{
		$this->vin = $vin ?: null;
	}


	/**
	 * @return string|null
	 */
	public function getVin(): ?string
	{
		return $this->vin;
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
	 * @param  int  $priceTow
	 * @return void
	 */
	public function setPriceTow(int $priceTow): void
	{
		$this->priceTow = $priceTow;
	}


	/**
	 * @return int
	 */
	public function getPriceTow(): int
	{
		return $this->priceTow;
	}


	/**
	 * @param  int  $priceRecall
	 * @return void
	 */
	public function setPriceRecall(int $priceRecall): void
	{
		$this->priceRecall = $priceRecall;
	}


	/**
	 * @return int
	 */
	public function getPriceRecall(): int
	{
		return $this->priceRecall;
	}


	/**
	 * @param  Part  $part
	 * @return void
	 */
	public function addPart(Part $part): void
	{
		$this->parts->add($part);
	}


	/**
	 * @param  int  $partId
	 * @return Part
	 */
	public function getPart(int $partId): Part
	{
		return $this->parts->get($partId);
	}


	/**
	 * @return Part[]
	 */
	public function getParts(): iterable
	{
		return $this->parts->toArray();
	}


	/**
	 * @param  Image  $image
	 * @return void
	 */
	public function addImage(Image $image): void
	{
		$this->images->add($image);
	}


	/**
	 * @param  int  $imageId
	 * @return Image
	 */
	public function getImage(int $imageId): Image
	{
		return $this->images->get($imageId);
	}


	/**
	 * @return Images[]
	 */
	public function getImages(): iterable
	{
		return $this->images->toArray();
	}


	/**
	 * @ORM\PreUpdate
	 * @param  PreUpdateEventArgs  $e
	 * @return void
	 * @internal
	 */
	public function onPreUpdate(PreUpdateEventArgs $e): void
	{
		Debugger::log(json_encode(['id' => $this->id] + $e->getEntityChangeSet()), 'changes/vehicle');
		$this->setModified(null);
	}
}
