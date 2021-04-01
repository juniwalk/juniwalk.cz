<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

namespace App\Forms\Factory;

use App\Forms\AuthSignUpForm;

interface AuthSignUpFormFactory
{
    /**
     * @return AuthSignUpForm
     */
    public function create(): AuthSignUpForm;
}
