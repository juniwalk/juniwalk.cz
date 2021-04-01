<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\DataGrids\Factory;

use App\Entity\Vehicle;
use App\DataGrids\VehiclePartGrid;

interface VehiclePartGridFactory
{
	/**
	 * @param  Vehicle|null  $vehicle
	 * @return VehiclePartGrid
	 */
	public function create(?Vehicle $vehicle): VehiclePartGrid;
}
