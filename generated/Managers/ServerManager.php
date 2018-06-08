<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\ServerListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\ServerResponse;
use EmsShield\Api\Resources\Server;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * Server manager class
 * 
 * @package EmsShield\Api\Managers
 */
class ServerManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Server manager class constructor
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
	 * Show server list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ServerListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/server';

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

		$response = new ServerListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Server(
					$this->apiClient, 
					$data['id'], 
					$data['project_id'], 
					$data['user_id'], 
					$data['name'], 
					$data['ip'], 
					$data['port'], 
					(isset($data['login']) ? $data['login'] : null), 
					$data['enabled'], 
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
	 * Create and store a new server
	 * 
	 * Excepted HTTP code : 201
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
	public function create($project_id, $name, $ip, $login, $enabled, $port = null, $position = null)
	{
		$routeUrl = '/api/server';

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
	 * Get specified server
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $serverId Server UUID
	 * 
	 * @return ServerResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($serverId)
	{
		$routePath = '/api/server/{serverId}';

		$pathReplacements = [
			'{serverId}' => $serverId,
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
	 * Update a specified server
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $serverId Server UUID
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
	public function update($serverId, $project_id, $name, $ip, $login, $enabled, $port = null, $position = null)
	{
		$routePath = '/api/server/{serverId}';

		$pathReplacements = [
			'{serverId}' => $serverId,
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
	 * @param string $serverId Server UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($serverId)
	{
		$routePath = '/api/server/{serverId}';

		$pathReplacements = [
			'{serverId}' => $serverId,
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
