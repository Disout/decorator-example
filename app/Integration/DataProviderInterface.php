<?php
declare(strict_types=1);

namespace App\Integration;

/**
 * Interface DataProviderInterface
 *
 * @package App\Integration
 */
interface DataProviderInterface
{
    /**
     * @param RequestParams $requestParams
     *
     * @return array
     */
    public function getResponse(RequestParams $requestParams) : array;
}