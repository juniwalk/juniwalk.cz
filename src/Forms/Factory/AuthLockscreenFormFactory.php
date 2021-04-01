<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

namespace App\Forms\Factory;

use App\Forms\AuthLockscreenForm;

interface AuthLockscreenFormFactory
{
    /**
     * @return AuthLockscreenForm
     */
    public function create(): AuthLockscreenForm;
}
