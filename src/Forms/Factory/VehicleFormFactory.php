<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

namespace App\Forms\Factory;

use App\Entity\Vehicle;
use App\Forms\VehicleForm;

interface VehicleFormFactory
{
    /**
     * @param  Vehicle|null  $vehicle
     * @return VehicleForm
     */
    public function create(?Vehicle $vehicle): VehicleForm;
}
