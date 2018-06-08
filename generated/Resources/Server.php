<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * Server resource class
 * 
 * @package EmsShield\Api\Resources
 */
class Server 
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
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $project_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $user_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $ip;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $port;

	/**
	 * @var string
	 */
	public $login;

	/**
	 * @var boolean
	 */
	public $enabled;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $position;

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
	 * Server resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $user_id Format: uuid.
	 * @param string $name
	 * @param string $ip
	 * @param int $port Format: int32.
	 * @param string $login
	 * @param boolean $enabled
	 * @param int $position Format: int32.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $project_id = null, $user_id = null, $name = null, $ip = null, $port = null, $login = null, $enabled = null, $position = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->project_id = $project_id;
		$this->user_id = $user_id;
		$this->name = $name;
		$this->ip = $ip;
		$this->port = $port;
		$this->login = $login;
		$this->enabled = $enabled;
		$this->position = $position;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified server
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $name
	 * @param string $ip
	 * @param string $login
	 * @param boolean $enabled
	 * @param mixed $port
	 * @param mixed $position
	 * 
	 * @return ServerResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($project_id, $name, $ip, $login, $enabled, $port = null, $position = null)
	{
		$routePath = '/api/server/{serverId}';

		$pathReplacements = [
			'{serverId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['ip'] = $ip;
		$bodyParameters['login'] = $login;
		$bodyParameters['enabled'] = $enabled;

		if (!is_null($port)) {
			$bodyParameters['port'] = $port;
		}

		if (!is_null($position)) {
			$bodyParameters['position'] = $position;
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

		$response = new ServerResponse(
			$this->apiClient, 
			new Server(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['user_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['ip'], 
				$requestBody['data']['port'], 
				(isset($requestBody['data']['login']) ? $requestBody['data']['login'] : null), 
				$requestBody['data']['enabled'], 
				$requestBody['data']['position'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified server
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/server/{serverId}';

		$pathReplacements = [
			'{serverId}' => $this->id,
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
	 * Show server server log list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ServerLogListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getServerLogs($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/server/{serverId}/serverLog';

		$pathReplacements = [
			'{serverId}' => $this->id,
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

		$response = new ServerLogListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new ServerLog(
					$this->apiClient, 
					$data['id'], 
					$data['server_id'], 
					$data['deploy_task_id'], 
					$data['status'], 
					$data['output'], 
					$data['position'], 
					$data['started_at'], 
					$data['finished_at'], 
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
