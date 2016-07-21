<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

final class HomePresenter extends BasePresenter
{
	/** @var \JuniWalk\Ubergrid\IGridFactory @inject */
	public $ubergrid;


	/**
	 * @param string  $name
	 */
	protected function createComponentUbergrid(string $name)
	{
		return $this->ubergrid->create();
	}
}
