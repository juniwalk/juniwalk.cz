<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   Forms
 * @link      https://github.com/juniwalk/forms
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\DI;

use Doctrine\DBAL\Types\Type;
use Kdyby\Doctrine\Connection;
use Nette\PhpGenerator\ClassType;
use Ramsey\Uuid\Doctrine\UuidType;

final class UuidDoctrineExtension extends \Nette\DI\CompilerExtension
{
	/**
	 * Register UUID type into doctrine.
	 * @param ClassType  $container
	 */
	public function afterCompile(ClassType $container)
	{
		$initialize = $container->getMethod('initialize');
		$builder = $this->getContainerBuilder();

		$initialize->addBody('?::addType(?, ?);', [Type::class, 'uuid', UuidType::class]);

		foreach ($builder->findByType(Connection::class) as $name => $connection) {
			$initialize->addBody('$this->getService(?)->getDatabasePlatform()->registerDoctrineTypeMapping(?, ?);', [$name, 'uuid', 'uuid']);
		}
	}
}
