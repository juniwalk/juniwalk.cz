<?php

/**
 * @author    Martin Procházka <juniwalk@outlook.cz>
 * @package   www.juniwalk.cz
 * @link      https://github.com/juniwalk/www.juniwalk.cz
 * @copyright Martin Procházka (c) 2015
 * @license   MIT License
 */

namespace App\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Tracy\ILogger;

final class ErrorPresenter implements \Nette\Application\IPresenter
{
	/**
     * Instance of the Logger class.
     * @var ILogger
     */
	private $logger;


    /**
     * Collect dependencies of this presenter.
     * @param ILogger  $logger  Logger instance
     */
	public function __construct(ILogger $logger)
	{
		$this->logger = $logger;
	}


    /**
     * Run the presenter logic by processing the request.
     * @param  Request  $request
     * @return \Nette\Application\IResponse
     */
    public function run(Request $request)
    {
        $exception = $request->getParameter('exception');

        if ($exception instanceof BadRequestException) {
            return new ForwardResponse($request->setPresenterName('Error4xx'));
        }

        $this->logger->log($exception, ILogger::EXCEPTION);

        return new CallbackResponse(function () {
            require __DIR__.'/templates/Error/500.phtml';
        });
    }
}
