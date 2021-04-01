<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\DataGrids;

use App\Entity\VehicleRepository;
use Ublaboo\DataGrid\DataGrid;

final class VehicleGrid extends AbstractGrid
{
	/** @var VehicleRepository */
	private $vehicleRepository;


	/**
	 * @param VehicleRepository  $vehicleRepository
	 */
	public function __construct(
		VehicleRepository $vehicleRepository
	) {
		$this->vehicleRepository = $vehicleRepository;

		$this->setTitle('nette.controls.vehicle-grid');
	}


	public function handleRemove(int $id): void
	{

	}


	/**
	 * @return iterable
	 */
	protected function createModel()//: iterable
	{
		return $this->vehicleRepository->createQueryBuilder('e', 'e.id');
	}


	/**
	 * @param  string  $name
	 * @return DataGrid
	 */
	protected function createComponentGrid(string $name): DataGrid
	{
		$grid = $this->createGrid($name);

		$grid->addColumnText('name', 'nette.vehicle.name')->setSortable();
		$grid->addColumnText('vin', 'nette.vehicle.vin')->setSortable();
		$grid->addColumnText('price', 'nette.vehicle.price')->setSortable();
		$grid->addColumnText('id', 'nette.general.id')->setAlign('right')->setSortable();

		//$this->createFilters($grid);
		$this->createActions($grid);

		$grid->setDefaultSort(['name' => 'ASC']);
		return $grid;
	}


	/**
	 * @param  DataGrid  $grid
	 * @return void
	 */
	private function createActions(DataGrid $grid): void
	{
        $grid->addToolbarButton('Vehicle:create', 'nette.general.create')
            ->setClass('btn btn-success btn-sm')->setIcon('plus');

		$grid->addAction('Vehicle:detail', '')->setIcon('pencil-alt')
			->setClass('btn btn-primary btn-xs')
			->setTitle('nette.general.edit');

		$grid->addAction('remove!', '')->setIcon('trash-alt')
			//->setConfirm('nette.message.confirm-deletion', 'name')
			->setClass('btn btn-danger btn-xs ajax')
			->setTitle('nette.general.remove');
	}
}
