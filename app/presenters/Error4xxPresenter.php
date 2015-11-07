<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

final class Error4xxPresenter extends \Nette\Application\UI\Presenter
{
    /**
     * Render - Default action of the 4xx error presenter.
     * @param Exception  $exception
     */
	public function renderDefault(\Exception $exception)
	{
		// load template 403.latte or 404.latte or ... 4xx.latte
		$file = __DIR__."/templates/Error/{$exception->getCode()}.latte";
		$this->template->setFile(is_file($file) ? $file : __DIR__.'/templates/Error/4xx.latte');
	}
}
