<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * Ip resource class
 * 
 * @package EmsShield\Api\Resources
 */
class Ip 
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
	public $project_id;

	/**
	 * Format: ip.
	 * 
	 * @var string
	 */
	public $ip;

	/**
	 * @var string
	 */
	public $ip_status_id;

	/**
	 * @var boolean
	 */
	public $v6;

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
	 * Ip resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $project_id Format: uuid.
	 * @param string $ip Format: ip.
	 * @param string $ip_status_id
	 * @param boolean $v6
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $project_id = null, $ip = null, $ip_status_id = null, $v6 = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->project_id = $project_id;
		$this->ip = $ip;
		$this->ip_status_id = $ip_status_id;
		$this->v6 = $v6;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified ip
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $ip
	 * @param string $ip_status_id
	 * @param boolean $v6
	 * @param string $log_entry
	 * @param mixed $tags
	 * 
	 * @return IpResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($project_id, $ip, $ip_status_id, $v6, $log_entry = null, $tags = null)
	{
		$routePath = '/api/ip/{projectId},{ip}';

		$pathReplacements = [
			'{projectId}' => $this->project_id,
			'{ip}' => $this->ip,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['ip'] = $ip;
		$bodyParameters['ip_status_id'] = $ip_status_id;
		$bodyParameters['v6'] = $v6;

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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/ip/{projectId},{ip}';

		$pathReplacements = [
			'{projectId}' => $this->project_id,
			'{ip}' => $this->ip,
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
	
	/**
	 * Show ip ip logs list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return IpLogListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getIpLogs($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/ip/{projectId},{ip}/ipLog';

		$pathReplacements = [
			'{projectId}' => $this->project_id,
			'{ip}' => $this->ip,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

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

		$response = new IpLogListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new IpLog(
					$this->apiClient, 
					$data['id'], 
					$data['project_id'], 
					$data['ip'], 
					$data['ip_status_id'], 
					$data['user_id'], 
					$data['entry'], 
					$data['position'], 
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
	 * Show ip assigned project tag list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ProjectTagListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getAssignedProjectTags($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/ip/{projectId},{ip}/assignedProjectTag';

		$pathReplacements = [
			'{projectId}' => $this->project_id,
			'{ip}' => $this->ip,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

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

		$response = new ProjectTagListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new ProjectTag(
					$this->apiClient, 
					$data['project_id'], 
					$data['name'], 
					$data['color'], 
					(isset($data['ip_status_id']) ? $data['ip_status_id'] : null), 
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
}
