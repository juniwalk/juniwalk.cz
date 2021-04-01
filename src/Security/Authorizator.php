<?php declare(strict_types=1);

/**
 * @copyright Design Point, s.r.o. (c) 2016
 * @license   MIT License
 */

namespace App\Security;

use App\Entity\Enums\Role;
use Nette\Security\Permission;

final class Authorizator extends Permission
{
	public function __construct()
	{
		foreach ((new Role)->getMap() as $role => $parent) {
			$this->addRole($role, $parent);
		}

		$this->createResources();
		$this->grantPrivileges();
	}


	/**
	 * @return void
	 */
	private function grantPrivileges(): void
	{
		// Guest rules
		$this->allow(Role::GUEST, 'Error');
		$this->allow(Role::GUEST, 'Error4xx');
		$this->allow(Role::GUEST, 'Auth');

		// User rules
		$this->allow(Role::USER, 'Home');
		$this->allow(Role::USER, 'Vehicle');

		// Admin has access anywhere
		$this->allow(Role::ADMIN);
	}


	/**
	 * @return void
	 */
	private function createResources(): void
	{
		// Guest resources
		$this->addResource('Error');
		$this->addResource('Error4xx');
		$this->addResource('Auth');

		// User resources
		$this->addResource('Home');
		$this->addResource('Vehicle');
	}
}
