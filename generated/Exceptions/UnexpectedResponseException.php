<?php

namespace EmsShield\Api\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Api Unexpected Response Exception class
 * 
 * @package EmsShield\Api\Exceptions
 */
class UnexpectedResponseException extends ApiException
{
	/**
	 * Response HTTP Code
	 *
	 * @var int
	 */
	protected $httpCode;

	/**
	 * Expected HTTP Code
	 *
	 * @var int
	 */
	protected $expectedHttpCode;

	/**
	 * Psr\Http\Message\ResponseInterface response
	 *
	 * @var ResponseInterface
	 */
	protected $response;

	/**
	 * Api exception response
	 *
	 * @var mixed
	 */
	protected $apiExceptionResponse;

	/**
	 * Construct the exception.
	 *
	 * @param string $httpCode Response HTTP Code
	 * @param string $expectedHttpCode Expected response HTTP Code
	 * @param ResponseInterface $response Psr\Http\Message\ResponseInterface response
	 * @param mixed|null [optional] $apiExceptionResponse Api exception response
	 * @param string $message|null [optional] The Exception message to throw.
	 * @param int $code [optional] The Exception code.
	 * @param Exception $previous [optional] The previous exception used for the exception chaining.
	 */
	public function __construct($httpCode, $expectedHttpCode, ResponseInterface $response, $apiExceptionResponse = null, $message = null, $code = 0, Exception $previous = null) {
		$this->httpCode = $httpCode;
		$this->expectedHttpCode = $expectedHttpCode;
		$this->response = $response;
		$this->apiExceptionResponse = $apiExceptionResponse;

		if (is_null($message)) {
			$message = 'Unexpected response HTTP code : ' . $this->httpCode . ' instead of ' . $this->expectedHttpCode;
		}

		parent::__construct($message, $code, $previous);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * Return the response HTTP Code
	 *
	 * @return int
	 */
	public function getHttpCode()
	{
		return $this->httpCode;
	}

	/**
	 * Return the expected response HTTP Code
	 *
	 * @return int
	 */
	public function getExpectedHttpCode()
	{
		return $this->expectedHttpCode;
	}

	/**
	 * Return the Psr\Http\Message\ResponseInterface response
	 *
	 * @return ResponseInterface
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * Return Api exception response
	 *
	 * @return mixed
	 */
	public function getApiExceptionResponse()
	{
		return $this->apiExceptionResponse;
	}
}