<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\UserListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\UserResponse;
use EmsShield\Api\Resources\User;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * User manager class
 * 
 * @package EmsShield\Api\Managers
 */
class UserManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * User manager class constructor
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
	 * User list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return UserListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/user';

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

		$response = new UserListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new User(
					$this->apiClient, 
					$data['id'], 
					$data['user_group_id'], 
					$data['name'], 
					$data['email'], 
					(isset($data['password']) ? $data['password'] : null), 
					$data['preferred_language'], 
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
	 * Create and store new user
	 * 
	 * Excepted HTTP code : 201
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
	public function create($user_group_id, $name, $email, $password, $preferred_language = null)
	{
		$routeUrl = '/api/user';

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
	 * Get specified user
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $userId User UUID
	 * 
	 * @return UserResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($userId)
	{
		$routePath = '/api/user/{userId}';

		$pathReplacements = [
			'{userId}' => $userId,
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
	 * Update a specified user
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $userId User UUID
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
	public function update($userId, $user_group_id, $name, $email, $password, $preferred_language = null)
	{
		$routePath = '/api/user/{userId}';

		$pathReplacements = [
			'{userId}' => $userId,
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
	 * @param string $userId User UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($userId)
	{
		$routePath = '/api/user/{userId}';

		$pathReplacements = [
			'{userId}' => $userId,
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
