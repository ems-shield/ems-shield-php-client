<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * User resource class
 * 
 * @package EmsShield\Api\Resources
 */
class User 
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
	public $user_group_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * Format: email.
	 * 
	 * @var string
	 */
	public $email;

	/**
	 * Format: password.
	 * 
	 * @var string
	 */
	public $password;

	/**
	 * @var string
	 */
	public $preferred_language;

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
	 * User resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $user_group_id Format: uuid.
	 * @param string $name
	 * @param string $email Format: email.
	 * @param string $password Format: password.
	 * @param string $preferred_language
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $user_group_id = null, $name = null, $email = null, $password = null, $preferred_language = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->user_group_id = $user_group_id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
		$this->preferred_language = $preferred_language;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified user
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $user_group_id
	 * @param string $name
	 * @param string $email Format: email.
	 * @param string $password Format: password.
	 * @param string $preferred_language
	 * 
	 * @return UserResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($user_group_id, $name, $email, $password, $preferred_language = null)
	{
		$routePath = '/api/user/{userId}';

		$pathReplacements = [
			'{userId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['user_group_id'] = $user_group_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['email'] = $email;
		$bodyParameters['password'] = $password;

		if (!is_null($preferred_language)) {
			$bodyParameters['preferred_language'] = $preferred_language;
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

		$response = new UserResponse(
			$this->apiClient, 
			new User(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['user_group_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['email'], 
				(isset($requestBody['data']['password']) ? $requestBody['data']['password'] : null), 
				$requestBody['data']['preferred_language'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified user
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/user/{userId}';

		$pathReplacements = [
			'{userId}' => $this->id,
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
	 * User project list
	 * 
	 * You can specify a GET parameter `user_role_id` to filter results.
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $user_role_id
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ProjectListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function index($user_role_id = null, $include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/user/{userId}/project';

		$pathReplacements = [
			'{userId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($user_role_id)) {
			$queryParameters['user_role_id'] = $user_role_id;
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

		$response = new ProjectListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Project(
					$this->apiClient, 
					$data['id'], 
					$data['name'], 
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
	 * User ip logs list
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
		$routePath = '/api/user/{userId}/ipLog';

		$pathReplacements = [
			'{userId}' => $this->id,
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
}
