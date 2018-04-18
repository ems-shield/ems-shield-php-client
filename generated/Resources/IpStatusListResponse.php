<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * IpStatusListResponse resource class
 * 
 * @package EmsShield\Api\Resources
 */
class IpStatusListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var IpStatus[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * IpStatusListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param IpStatus[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
