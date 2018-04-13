<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * Notification resource class
 * 
 * @package EmsShield\Api\Resources
 */
class Notification 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var string
	 */
	public $notifiable_id;

	/**
	 * @var string
	 */
	public $notifiable_type;

	/**
	 * @var object
	 */
	public $data;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $read_at;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $created_at;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $updated_at;

	/**
	 * Notification resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $type
	 * @param string $notifiable_id
	 * @param string $notifiable_type
	 * @param object $data
	 * @param string $read_at Format: date-time.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $type = null, $notifiable_id = null, $notifiable_type = null, $data = null, $read_at = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->type = $type;
		$this->notifiable_id = $notifiable_id;
		$this->notifiable_type = $notifiable_type;
		$this->data = $data;
		$this->read_at = $read_at;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
}
