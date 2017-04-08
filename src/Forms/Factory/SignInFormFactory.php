<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Forms\Factory;

use App\Forms\SignInForm;

interface SignInFormFactory
{
	/**
	 * @return SignInForm
	 */
	public function create() : SignInForm;
}
