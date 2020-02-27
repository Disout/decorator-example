<?php
declare(strict_types=1);

namespace App\Controller;

use App\Decorator\CacheIntegrationProviderDecorator;
use App\Decorator\LoggerIntegrationProviderDecorator;
use App\Integration\Account;
use App\Integration\DataProvider;
use App\Integration\RequestParams;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

/**
 * Class LessonController
 *
 * @package App\Controller
 */
class LessonController
{
    /**
     * @var bool
     */
    public $isProduction;

    /**
     * @var string
     */
    public $host;
    /**
     * @var string
     */
    public $user;
    /**
     * @var string
     */
    public $password;

    /**
     * @var CacheItemPoolInterface
     */
    public $nullCache;

    /**
     * @var CacheItemPoolInterface
     */
    public $memcacheCache;

    /**
     * @var LoggerInterface
     */
    public $fileLogger;

    /**
     * @var LoggerInterface
     */
    public $kibanaLogger;

    /**
     * LessonController constructor.
     *
     * @param bool    $isProduction
     */
    public function __construct(bool $isProduction = false)
    {
        $this->isProduction = $isProduction;
    }

    /**
     * @param int         $category
     * @param string|null $responseType
     */
    public function getList(int $category, string $responseType = ''): void
    {
        if (!preg_match('/[0-9]{5}/', $category) || $category <= 0) {
            echo "Категория должна быть больше 0 и состоять из 5 цифр";

            return;
        }

        if ($responseType === '') {
            $responseType = 'json';
        }
        $account = new Account($this->host, $this->user, $this->password);

        $requestParams = new RequestParams(['categoryId' => $category]);

        $dataProvider = new DataProvider($account);

        $cacheDecorator = new CacheIntegrationProviderDecorator(
            $dataProvider,
            $this->isProduction ? $this->memcacheCache : $this->nullCache
        );

        $loggerDecorator = new LoggerIntegrationProviderDecorator(
            $cacheDecorator,
            $this->isProduction ? $this->kibanaLogger : $this->fileLogger
        );

        $data = $loggerDecorator->getResponse($requestParams);

        if ($data === []) {
            echo "Уроки не найдены";

            return;
        }

        // Можно обернуть в отдельный интерфейс и классы при необходимости
        switch ($responseType) {
            case 'xml':
                $xml = new SimpleXMLElement('<root/>');
                array_walk_recursive($data, [$xml, 'addChild']);
                echo $xml->asXML();

                return;
            case 'json':
                echo json_encode($data);

                return;
        }
    }
}