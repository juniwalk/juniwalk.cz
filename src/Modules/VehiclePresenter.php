<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Modules;

use App\DataGrids\Factory\VehicleGridFactory;
use App\DataGrids\Factory\VehiclePartGridFactory;
use App\DataGrids\VehicleGrid;
use App\DataGrids\VehiclePartGrid;
use App\Entity\Vehicle;
use App\Entity\VehicleRepository;
use App\Forms\Factory\VehicleFormFactory;
use App\Forms\VehicleForm;

final class VehiclePresenter extends AbstractPresenter
{
	/** @var VehicleFormFactory */
	private $vehicleFormFactory;

	/** @var VehicleGridFactory */
	private $vehicleGridFactory;

	/** @var VehiclePartGridFactory */
	private $vehiclePartGridFactory;

	/** @var VehicleRepository */
	private $vehicleRepository;

	/** @var Vehicle */
	private $vehicle;


	/**
	 * @param VehicleRepository  $vehicleRepository
	 * @param VehicleFormFactory  $vehicleFormFactory
	 * @param VehicleGridFactory  $vehicleGridFactory
	 * @param VehiclePartGridFactory  $vehiclePartGridFactory
	 */
	public function __construct(
		VehicleRepository $vehicleRepository,
		VehicleFormFactory $vehicleFormFactory,
		VehicleGridFactory $vehicleGridFactory,
		VehiclePartGridFactory $vehiclePartGridFactory
	) {
		$this->vehicleFormFactory = $vehicleFormFactory;
		$this->vehicleGridFactory = $vehicleGridFactory;
		$this->vehiclePartGridFactory = $vehiclePartGridFactory;
		$this->vehicleRepository = $vehicleRepository;
	}


	/**
	 * @param  int  $id
	 * @return void
	 */
	public function actionDetail(int $id): void
	{
		$this->vehicle = $this->vehicleRepository->getById($id);
	}


	/**
	 * @param  string  $name
	 * @return VehicleForm
	 */
	protected function createComponentVehicleForm(string $name): VehicleForm
	{
		$form = $this->vehicleFormFactory->create($this->vehicle);
		$form->onSuccess[] = function($frm, $data) use ($form) {
	    	if ($frm['apply']->isSubmittedBy() && $vehicle = $form->getVehicle()) {
				$this->redirect('detail', ['id' => $vehicle->getId()]);
	    	}

			$this->redirect('default');
		};

		return $form;
	}


	/**
	 * @param  string  $name
	 * @return VehicleGrid
	 */
	protected function createComponentVehicleGrid(string $name): VehicleGrid
	{
		return $this->vehicleGridFactory->create();
	}


	/**
	 * @param  string  $name
	 * @return VehiclePartGrid
	 */
	protected function createComponentVehiclePartGrid(string $name): VehiclePartGrid
	{
		return $this->vehiclePartGridFactory->create($this->vehicle);
	}
}
