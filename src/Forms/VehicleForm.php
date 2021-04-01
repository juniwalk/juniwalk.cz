<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

namespace App\Forms;

use App\Entity\Vehicle;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Nette\Application\UI\Form;
use Nette\Security;
use Nette\Utils\ArrayHash;
use JuniWalk\Form\AbstractForm;

final class VehicleForm extends AbstractForm
{
	/** @var EntityManager */
	private $entityManager;

	/** @var Vehicle */
	private $vehicle;

	/** @var User */
	private $user;


	/**
	 * @param Vehicle|null  $vehicle
	 * @param Security\User  $user
	 * @param EntityManager  $entityManager
	 */
	public function __construct(
		?Vehicle $vehicle,
		Security\User $user,
		EntityManager $entityManager
	) {
		$this->entityManager = $entityManager;
		$this->user = $user->getIdentity();
		$this->vehicle = $vehicle;

		$this->setTemplateFile(__DIR__.'/templates/vehicleForm.latte');
		$this->onBeforeRender[] = function($form, $template) {
			$this->setDefaults($this->vehicle);
		};
	}


	/**
	 * @return Vehicle|null
	 */
	public function getVehicle(): ?Vehicle
	{
		return $this->vehicle;
	}


	/**
	 * @param  Vehicle|null  $vehicle
	 * @return void
	 */
	public function setDefaults(?Vehicle $vehicle): void
	{
		$form = $this->getForm();

		if (!isset($vehicle)) {
			return;
		}

		$form->setDefaults([
			'name' => $vehicle->getName(),
			'vin' => $vehicle->getVin(),
			'price' => $vehicle->getPrice(),
			'priceTow' => $vehicle->getPriceTow(),
			'priceRecall' => $vehicle->getPriceRecall(),
		]);
	}


	/**
	 * @param  string  $name
	 * @return Form
	 */
	protected function createComponentForm(string $name): Form
	{
		$form = parent::createComponentForm($name);
		$form->addText('name')->setRequired('nette.vehicle.name-required');
		$form->addText('vin');
		$form->addInteger('price')->setRequired('nette.price.name-required');
		$form->addInteger('priceTow');
		$form->addInteger('priceRecall');

		$form->addSubmit('submit');
		$form->addSubmit('apply');

		return $form;
	}


	/**
	 * @param Form  $form
	 * @param ArrayHash  $data
	 */
	protected function handleSuccess(Form $form, ArrayHash $data): void
	{
		if (!$vehicle = $this->getVehicle()) {
			$vehicle = new Vehicle($data->name, $data->price, $this->user);
		}

		$vehicle->setName($data->name);
		$vehicle->setVin($data->vin);
		$vehicle->setPrice($data->price);
		$vehicle->setPriceTow($data->priceTow);
		$vehicle->setPriceRecall($data->priceRecall);

		try {
			$this->entityManager->persist($vehicle);
			$this->entityManager->flush();
			$this->vehicle = $vehicle;

		} catch (UniqueConstraintViolationException $e) {
			$form->addError('nette.message.something-went-wrong');
		}
	}
}
