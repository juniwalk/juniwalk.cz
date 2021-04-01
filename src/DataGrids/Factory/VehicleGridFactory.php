<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\DataGrids\Factory;

use App\DataGrids\VehicleGrid;

interface VehicleGridFactory
{
	/**
	 * @return VehicleGrid
	 */
	public function create(): VehicleGrid;
}
