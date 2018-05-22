<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\ProjectTagListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\ProjectTagResponse;
use EmsShield\Api\Resources\ProjectTag;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * ProjectTag manager class
 * 
 * @package EmsShield\Api\Managers
 */
class ProjectTagManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * ProjectTag manager class constructor
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
	 * Show project tag list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ProjectTagListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/projectTag';

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
	
	/**
	 * Create and store a new project tag
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $name
	 * @param string $color
	 * 
	 * @return ProjectTagResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($project_id, $name, $color = null)
	{
		$routeUrl = '/api/projectTag';

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['name'] = $name;

		if (!is_null($color)) {
			$bodyParameters['color'] = $color;
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

		$response = new ProjectTagResponse(
			$this->apiClient, 
			new ProjectTag(
				$this->apiClient, 
				$requestBody['data']['project_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['color'], 
				(isset($requestBody['data']['ip_status_id']) ? $requestBody['data']['ip_status_id'] : null), 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified project tag
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project ID
	 * @param string $name Name
	 * 
	 * @return ProjectTagResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($projectId, $name)
	{
		$routePath = '/api/projectTag/{projectId},{projectTagName}';

		$pathReplacements = [
			'{projectId}' => $projectId,
			'{name}' => $name,
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

		$response = new ProjectTagResponse(
			$this->apiClient, 
			new ProjectTag(
				$this->apiClient, 
				$requestBody['data']['project_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['color'], 
				(isset($requestBody['data']['ip_status_id']) ? $requestBody['data']['ip_status_id'] : null), 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified project tag
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project ID
	 * @param string $name Name
	 * @param string $project_id Format: uuid.
	 * @param string $ip
	 * @param string $color
	 * 
	 * @return ProjectTagResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($projectId, $name, $project_id, $ip, $color = null)
	{
		$routePath = '/api/projectTag/{projectId},{projectTagName}';

		$pathReplacements = [
			'{projectId}' => $projectId,
			'{name}' => $name,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['ip'] = $ip;

		if (!is_null($color)) {
			$bodyParameters['color'] = $color;
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

		$response = new ProjectTagResponse(
			$this->apiClient, 
			new ProjectTag(
				$this->apiClient, 
				$requestBody['data']['project_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['color'], 
				(isset($requestBody['data']['ip_status_id']) ? $requestBody['data']['ip_status_id'] : null), 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified project tag
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $projectId Project ID
	 * @param string $name Name
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($projectId, $name)
	{
		$routePath = '/api/projectTag/{projectId},{projectTagName}';

		$pathReplacements = [
			'{projectId}' => $projectId,
			'{name}' => $name,
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
