<?php

namespace EmsShield\Api\Tests;

use PHPUnit\Framework\TestCase;
use EmsShield\Api\ApiClient;

/**
 * ems-shield client test class (test for version 1.0)
 * 
 * @package EmsShield\Api\Tests
 */
class ApiClientTest extends TestCase
{
	public function testCanCreateClient()
	{
		$apiClient = new ApiClient(
			getenv('bearerToken'),
			getenv('apiBaseUrl')
		);

		$this->assertNotNull(
			$apiClient->getHttpClient()
		);
	}
}