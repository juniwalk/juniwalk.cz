<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Modules;

use DateTime;
use App\Entity\UserRepository;
use JuniWalk\Ubergrid\Grid;

final class HomePresenter extends AbstractPresenter
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;


	/**
	 * @param UserRepository  $userRepository
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}


	/**
	 * @param string  $name
	 */
	protected function createComponentDoctrineGrid(string $name)
	{
		$grid = new \Ublaboo\DataGrid\DataGrid($this, $name);
		$grid->setMultiSortEnabled(TRUE);

		$grid->addColumnText('id', '#')->setSortable();
		$grid->addColumnText('firstName', 'Jméno')->setSortable();
		$grid->addColumnText('lastName', 'Příjmení')->setSortable();
		$grid->addColumnText('email', 'Email')->setSortable();
		$grid->addColumnDateTime('signUp', 'Registrován')->setSortable();

		$grid->addFilterDate('signUp', 'User registerd on');

		$grid->setDefaultFilter(['signUp' => new DateTime]);

		return $grid->setDataSource(
			$this->userRepository->createQueryBuilder('u')
		);
	}


	/**
	 * @param string  $name
	 */
	protected function createComponentArrayGrid(string $name)
	{
		$grid = $this->createComponentDoctrineGrid($name);

		return $grid->setDataSource([
			['id' => 1, 'firstName' => 'Pavel', 'lastName' => 'Janda', 'email' => 'paveljanda@github.com', 'signUp' => DateTime::createFromFormat('Y-m-d H:i:s', '2016-09-14 14:25:45')],
			['id' => 2, 'firstName' => 'Jakub', 'lastName' => 'Kontra', 'email' => 'jakubkontra@github.com', 'signUp' => DateTime::createFromFormat('Y-m-d H:i:s', '2016-11-04 09:10:17')],
			['id' => 3, 'firstName' => 'Martin', 'lastName' => 'Procházka', 'email' => 'juniwalk@outlook.cz', 'signUp' => DateTime::createFromFormat('Y-m-d H:i:s', '2015-12-16 17:06:33')],
			['id' => 4, 'firstName' => 'Filip', 'lastName' => 'Procházka', 'email' => 'fprochazka@github.com', 'signUp' => DateTime::createFromFormat('Y-m-d H:i:s', '2016-04-01 08:40:00')],
		]);
	}


	/**
	 * @param string  $name
	 */
	protected function createComponentUbergrid(string $name)
	{
		return new Grid;
	}
}
