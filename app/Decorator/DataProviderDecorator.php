<?php
declare(strict_types=1);

namespace App\Decorator;

use App\Integration\DataProviderInterface;
use App\Integration\RequestParams;

/**
 * Class DataProviderDecorator
 *
 * @package App\Decorator
 */
class DataProviderDecorator implements DataProviderInterface
{
    /**
     * @var DataProviderInterface
     */
    protected $innerIntegrationProvider;

    /**
     * DataProviderDecorator constructor.
     *
     * @param DataProviderInterface $integrationProvider
     */
    function __construct(DataProviderInterface $integrationProvider)
    {
        $this->innerIntegrationProvider = $integrationProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(RequestParams $requestParams): array
    {
        return $this->innerIntegrationProvider->getResponse($requestParams);
    }
}