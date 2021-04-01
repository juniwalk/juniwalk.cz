<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\DataGrids;

use App\Entity\PartRepository;
use App\Entity\Vehicle;
use Ublaboo\DataGrid\DataGrid;

final class VehiclePartGrid extends AbstractGrid
{
	/** @var PartRepository */
	private $partRepository;

	/** @var Vehicle */
	private $vehicle;


	/**
	 * @param Vehicle|null  $vehicle
	 * @param PartRepository  $partRepository
	 */
	public function __construct(
		?Vehicle $vehicle,
		PartRepository $partRepository
	) {
		$this->partRepository = $partRepository;
		$this->vehicle = $vehicle;

		$this->setTitle('nette.controls.part-grid');
		$this->setDisabled(!$vehicle);
	}


	public function handleRemove(int $id): void
	{

	}


	/**
	 * @return iterable
	 */
	protected function createModel()//: iterable
	{
		if (!$this->vehicle) {
			return [];
		}

		return $this->partRepository->createQueryBuilder('e', 'e.id')
			->where('e.vehicle = :vehicle')
			->setParameter('vehicle', $this->vehicle);
	}


	/**
	 * @param  string  $name
	 * @return DataGrid
	 */
	protected function createComponentGrid(string $name): DataGrid
	{
		$grid = $this->createGrid($name);

		$grid->addColumnText('name', 'nette.part.name')->setSortable();
		$grid->addColumnText('price', 'nette.part.price')->setSortable();
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
