<?php
declare(strict_types=1);

namespace App\Integration;

/**
 * Class IntegrationRequestParams
 *
 * @package App\Integration
 */
class RequestParams
{
    /**
     * @var array
     */
    public $data;

    /**
     * RequestParams constructor.
     *
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }
}