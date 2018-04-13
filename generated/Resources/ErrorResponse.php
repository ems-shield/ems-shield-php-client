<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * ErrorResponse resource class
 * 
 * @package EmsShield\Api\Resources
 */
class ErrorResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Error message
	 * 
	 * @var string
	 */
	public $message;

	/**
	 * Fields errors map
	 * 
	 * @var object
	 */
	public $errors;

	/**
	 * Error status code
	 * 
	 * @var int
	 */
	public $status_code;

	/**
	 * Debug mode extra info
	 * 
	 * @var mixed
	 */
	public $debug;

	/**
	 * ErrorResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $message Error message
	 * @param object $errors Fields errors map
	 * @param int $status_code Error status code
	 * @param mixed $debug Debug mode extra info
	 */
	public function __construct(ApiClient $apiClient, $message = null, $errors = null, $status_code = null, $debug = null)
	{
		$this->apiClient = $apiClient;
		$this->message = $message;
		$this->errors = $errors;
		$this->status_code = $status_code;
		$this->debug = $debug;
	}
}
