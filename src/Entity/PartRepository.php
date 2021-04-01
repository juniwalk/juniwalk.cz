<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Entity;

final class PartRepository extends AbstractRepository
{
	/** @var string */
	protected $entityName = Part::class;
}
