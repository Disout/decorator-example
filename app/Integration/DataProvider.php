<?php
declare(strict_types=1);

namespace App\Integration;

use Exception;

/**
 * Class DataProvider
 *
 * @package App\Integration
 */
class DataProvider implements DataProviderInterface
{
    /**
     * @var Account
     */
    private $account;

    function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param RequestParams $requestParams
     *
     * @return array
     * @throws Exception
     */
    public function getResponse(RequestParams $requestParams): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->account->host.'?'.http_build_query($requestParams->data));
        curl_setopt($ch, CURLOPT_USERPWD, $this->account->user.":".$this->account->password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($output, true);

        return is_array($result) ? $result : [];
    }
}