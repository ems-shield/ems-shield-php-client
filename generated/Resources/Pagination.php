<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * Pagination resource class
 * 
 * @package EmsShield\Api\Resources
 */
class Pagination 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $total;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $count;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $per_page;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $current_page;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $total_pages;

	/**
	 * @var mixed
	 */
	public $links;

	/**
	 * Pagination resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param int $total Format: int32.
	 * @param int $count Format: int32.
	 * @param int $per_page Format: int32.
	 * @param int $current_page Format: int32.
	 * @param int $total_pages Format: int32.
	 * @param mixed $links
	 */
	public function __construct(ApiClient $apiClient, $total = null, $count = null, $per_page = null, $current_page = null, $total_pages = null, $links = null)
	{
		$this->apiClient = $apiClient;
		$this->total = $total;
		$this->count = $count;
		$this->per_page = $per_page;
		$this->current_page = $current_page;
		$this->total_pages = $total_pages;
		$this->links = $links;
	}
}
