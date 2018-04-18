<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\IpStatusListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\IpStatusResponse;
use EmsShield\Api\Resources\IpStatus;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * IpStatus manager class
 * 
 * @package EmsShield\Api\Managers
 */
class IpStatusManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * IpStatus manager class constructor
	 *
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 */
	public function __construct(ApiClient $apiClient)
	{
		$this->apiClient = $apiClient;
	}

	/**
	 * Return the API client used for this manager requests
	 *
	 * @return ApiClient
	 */
	public function getApiClient()
	{
		return $this->apiClient;
	}

	/**
	 * Show ip status list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return IpStatusListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/ipStatus';

		$queryParameters = [];

		if (!is_null($include)) {
			$queryParameters['include'] = $include;
		}

		if (!is_null($search)) {
			$queryParameters['search'] = $search;
		}

		if (!is_null($page)) {
			$queryParameters['page'] = $page;
		}

		if (!is_null($limit)) {
			$queryParameters['limit'] = $limit;
		}

		if (!is_null($order_by)) {
			$queryParameters['order_by'] = $order_by;
		}

		$requestOptions = [];
		$requestOptions['query'] = $queryParameters;

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

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

		$response = new IpStatusListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new IpStatus(
					$this->apiClient, 
					$data['id']
				); 
			}, $requestBody['data']), 
			new Meta(
				$this->apiClient, 
				((isset($requestBody['meta']['pagination']) && !is_null($requestBody['meta']['pagination'])) ? (new Pagination(
					$this->apiClient, 
					$requestBody['meta']['pagination']['total'], 
					$requestBody['meta']['pagination']['count'], 
					$requestBody['meta']['pagination']['per_page'], 
					$requestBody['meta']['pagination']['current_page'], 
					$requestBody['meta']['pagination']['total_pages'], 
					$requestBody['meta']['pagination']['links']
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Create and store a new ip status
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $id
	 * 
	 * @return IpStatusResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($id)
	{
		$routeUrl = '/api/ipStatus';

		$bodyParameters = [];
		$bodyParameters['id'] = $id;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new IpStatusResponse(
			$this->apiClient, 
			new IpStatus(
				$this->apiClient, 
				$requestBody['data']['id']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified ip status
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $ipStatusId Ip Status ID
	 * 
	 * @return IpStatusResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($ipStatusId)
	{
		$routePath = '/api/ipStatus/{ipStatusId}';

		$pathReplacements = [
			'{ipStatusId}' => $ipStatusId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

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

		$response = new IpStatusResponse(
			$this->apiClient, 
			new IpStatus(
				$this->apiClient, 
				$requestBody['data']['id']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified ip status
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $ipStatusId Ip Status ID
	 * 
	 * @return IpStatusResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($ipStatusId)
	{
		$routePath = '/api/ipStatus/{ipStatusId}';

		$pathReplacements = [
			'{ipStatusId}' => $ipStatusId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

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

		$response = new IpStatusResponse(
			$this->apiClient, 
			new IpStatus(
				$this->apiClient, 
				$requestBody['data']['id']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified ip status
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $ipStatusId Ip Status ID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($ipStatusId)
	{
		$routePath = '/api/ipStatus/{ipStatusId}';

		$pathReplacements = [
			'{ipStatusId}' => $ipStatusId,
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
