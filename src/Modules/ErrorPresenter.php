<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Modules;

use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Tracy\ILogger;

final class ErrorPresenter implements \Nette\Application\IPresenter
{
	/** @var ILogger */
	private $logger;


	/**
	 * @param ILogger  $logger
	 */
	public function __construct(ILogger $logger)
	{
		$this->logger = $logger;
	}


	/**
	 * @param  Request  $request
	 * @return Nette\Application\IResponse
	 */
	public function run(Request $request) : IResponse
	{
		$exception = $request->getParameter('exception');

		if ($exception instanceof \Nette\Application\BadRequestException) {
			return new ForwardResponse($request->setPresenterName('Error4xx'));
		}

   		$this->logger->log($exception, ILogger::EXCEPTION);

		return new CallbackResponse(function () {
			require __DIR__.'/templates/Error/500.phtml';
		});
	}
}
