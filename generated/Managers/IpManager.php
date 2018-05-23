<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\IpListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\IpResponse;
use EmsShield\Api\Resources\Ip;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * Ip manager class
 * 
 * @package EmsShield\Api\Managers
 */
class IpManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Ip manager class constructor
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
	 * Show ip list
	 * 
	 * You can specify a GET parameter `ip_version` to filter results.
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $ip_version
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return IpListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($ip_version = null, $include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/ip';

		$queryParameters = [];

		if (!is_null($ip_version)) {
			$queryParameters['ip_version'] = $ip_version;
		}

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

		$response = new IpListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Ip(
					$this->apiClient, 
					$data['project_id'], 
					$data['ip'], 
					$data['ip_status_id'], 
					$data['v6'], 
					$data['expires_at'], 
					$data['created_at'], 
					$data['updated_at']
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
	 * Create and store a new ip
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $ip
	 * @param string $ip_status_id
	 * @param boolean $v6
	 * @param string $expires_at Must be a valid date according to the strtotime PHP function.
	 * @param string $log_entry
	 * @param mixed $tags
	 * 
	 * @return IpResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($project_id, $ip, $ip_status_id, $v6, $expires_at = null, $log_entry = null, $tags = null)
	{
		$routeUrl = '/api/ip';

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['ip'] = $ip;
		$bodyParameters['ip_status_id'] = $ip_status_id;
		$bodyParameters['v6'] = $v6;

		if (!is_null($expires_at)) {
			$bodyParameters['expires_at'] = $expires_at;
		}

		if (!is_null($log_entry)) {
			$bodyParameters['log_entry'] = $log_entry;
		}

		if (!is_null($tags)) {
			$bodyParameters['tags'] = $tags;
		}

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

		$response = new IpResponse(
			$this->apiClient, 
			new Ip(
				$this->apiClient, 
				$requestBody['data']['project_id'], 
				$requestBody['data']['ip'], 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['v6'], 
				$requestBody['data']['expires_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified ip
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project ID
	 * @param string $ip Ip
	 * 
	 * @return IpResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($projectId, $ip)
	{
		$routePath = '/api/ip/{projectId},{ip}';

		$pathReplacements = [
			'{projectId}' => $projectId,
			'{ip}' => $ip,
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

		$response = new IpResponse(
			$this->apiClient, 
			new Ip(
				$this->apiClient, 
				$requestBody['data']['project_id'], 
				$requestBody['data']['ip'], 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['v6'], 
				$requestBody['data']['expires_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified ip
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project ID
	 * @param string $ip
	 * @param string $project_id Format: uuid.
	 * @param string $ip_status_id
	 * @param boolean $v6
	 * @param string $expires_at Must be a valid date according to the strtotime PHP function.
	 * @param string $log_entry
	 * @param mixed $tags
	 * 
	 * @return IpResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($projectId, $ip, $project_id, $ip_status_id, $v6, $expires_at = null, $log_entry = null, $tags = null)
	{
		$routePath = '/api/ip/{projectId},{ip}';

		$pathReplacements = [
			'{projectId}' => $projectId,
			'{ip}' => $ip,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['ip'] = $ip;
		$bodyParameters['ip_status_id'] = $ip_status_id;
		$bodyParameters['v6'] = $v6;

		if (!is_null($expires_at)) {
			$bodyParameters['expires_at'] = $expires_at;
		}

		if (!is_null($log_entry)) {
			$bodyParameters['log_entry'] = $log_entry;
		}

		if (!is_null($tags)) {
			$bodyParameters['tags'] = $tags;
		}

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

		$response = new IpResponse(
			$this->apiClient, 
			new Ip(
				$this->apiClient, 
				$requestBody['data']['project_id'], 
				$requestBody['data']['ip'], 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['v6'], 
				$requestBody['data']['expires_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified ip
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $projectId Project ID
	 * @param string $ip Ip
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($projectId, $ip)
	{
		$routePath = '/api/ip/{projectId},{ip}';

		$pathReplacements = [
			'{projectId}' => $projectId,
			'{ip}' => $ip,
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
