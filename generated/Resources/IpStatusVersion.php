<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * IpStatusVersion resource class
 * 
 * @package EmsShield\Api\Resources
 */
class IpStatusVersion 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var string
	 */
	public $ip_status_id;

	/**
	 * @var string
	 */
	public $i18n_lang_id;

	/**
	 * @var string
	 */
	public $description;

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
	 * IpStatusVersion resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $ip_status_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $ip_status_id = null, $i18n_lang_id = null, $description = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->ip_status_id = $ip_status_id;
		$this->i18n_lang_id = $i18n_lang_id;
		$this->description = $description;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified ip status version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $ip_status_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return IpStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($ip_status_id, $i18n_lang_id, $description)
	{
		$routePath = '/api/ipStatusVersion/{ipStatusId},{i18nLangId}';

		$pathReplacements = [
			'{ipStatusId}' => $this->ip_status_id,
			'{i18nLangId}' => $this->i18n_lang_id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['ip_status_id'] = $ip_status_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;
		$bodyParameters['description'] = $description;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new IpStatusVersionResponse(
			$this->apiClient, 
			new IpStatusVersion(
				$this->apiClient, 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified ip status version
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/ipStatusVersion/{ipStatusId},{i18nLangId}';

		$pathReplacements = [
			'{ipStatusId}' => $this->ip_status_id,
			'{i18nLangId}' => $this->i18n_lang_id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 204) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 204, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ErrorResponse(
			$this->apiClient, 
			$requestBody['message'], 
			(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
			(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
			(isset($requestBody['debug']) ? $requestBody['debug'] : null)
		);

		return $response;
	}
}
