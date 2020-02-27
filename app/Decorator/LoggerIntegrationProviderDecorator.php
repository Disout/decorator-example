<?php
declare(strict_types=1);

namespace App\Decorator;

use App\Integration\DataProviderInterface;
use App\Integration\RequestParams;
use \Psr\Log\LoggerInterface;


    /**
     * Class LoggerIntegrationProviderDecorator
     *
     * @package App\Decorator
     */
class LoggerIntegrationProviderDecorator extends DataProviderDecorator
{
    /**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * LoggerIntegrationProviderDecorator constructor.
	 *
	 * @param DataProviderInterface $integrationProvider
	 * @param LoggerInterface $logger
	 */
	function __construct(DataProviderInterface $integrationProvider, LoggerInterface $logger)
	{
		parent::__construct($integrationProvider);
		$this->logger = $logger;
	}

	public function getResponse(RequestParams $requestParams): array
	{
		try {
			$response = parent::getResponse($requestParams);
		}
		catch (\Exception $e) {
//		    $this->logger->critical($e->getMessage());
			throw new $e;
		}

		return $response;
	}
}
