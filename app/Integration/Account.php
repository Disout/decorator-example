<?php
declare(strict_types=1);

namespace app\Integration;

/**
 * Class IntegrationAccount
 *
 * @package App\Integration
 */
class Account
{
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
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 */
	function __construct(string $host, string $user, string $password)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
	}
}