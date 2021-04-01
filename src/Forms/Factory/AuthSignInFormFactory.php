<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

namespace App\Forms\Factory;

use App\Forms\AuthSignInForm;

interface AuthSignInFormFactory
{
    /**
     * @return AuthSignInForm
     */
    public function create(): AuthSignInForm;
}
