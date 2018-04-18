<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * IpStatusResponse resource class
 * 
 * @package EmsShield\Api\Resources
 */
class IpStatusResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var IpStatus
	 */
	public $data;

	/**
	 * IpStatusResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param IpStatus $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
