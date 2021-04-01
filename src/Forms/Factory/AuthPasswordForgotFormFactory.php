<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2016
 * @license   MIT License
 */

namespace App\Forms\Factory;

use App\Forms\AuthPasswordForgotForm;

interface AuthPasswordForgotFormFactory
{
    /**
     * @return AuthPasswordForgotForm
     */
    public function create(): AuthPasswordForgotForm;
}
