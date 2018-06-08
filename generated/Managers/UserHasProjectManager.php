<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\UserHasProjectListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\UserHasProjectResponse;
use EmsShield\Api\Resources\UserHasProject;
use EmsShield\Api\Resources\UserResponse;
use EmsShield\Api\Resources\User;
use EmsShield\Api\Resources\ProjectResponse;
use EmsShield\Api\Resources\Project;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * UserHasProject manager class
 * 
 * @package EmsShield\Api\Managers
 */
class UserHasProjectManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * UserHasProject manager class constructor
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
	 * List of relationships between users and projects
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return UserHasProjectListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/userHasProject';

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

		$response = new UserHasProjectListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new UserHasProject(
					$this->apiClient, 
					$data['user_id'], 
					$data['project_id'], 
					$data['user_role_id'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['user']) && !is_null($data['user'])) ? (new UserResponse(
						$this->apiClient, 
						new User(
							$this->apiClient, 
							$data['user']['data']['id'], 
							$data['user']['data']['user_group_id'], 
							$data['user']['data']['name'], 
							$data['user']['data']['email'], 
							(isset($data['user']['data']['password']) ? $data['password'] : null), 
							$data['user']['data']['preferred_language'], 
							$data['user']['data']['created_at'], 
							$data['user']['data']['updated_at']
						)
					)) : null), 
					((isset($data['project']) && !is_null($data['project'])) ? (new ProjectResponse(
						$this->apiClient, 
						new Project(
							$this->apiClient, 
							$data['project']['data']['id'], 
							$data['project']['data']['name'], 
							$data['project']['data']['public_key'], 
							$data['project']['data']['last_run_at'], 
							$data['project']['data']['created_at'], 
							$data['project']['data']['updated_at']
						)
					)) : null)
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
	 * Create and store new relationship between a user and a project
	 * 
	 * <aside class="notice">Only one relationship per user/project is allowed and only one user can be <code>Owner</code>of a project.</aside>
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $user_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $user_role_id
	 * 
	 * @return UserHasProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($user_id, $project_id, $user_role_id)
	{
		$routeUrl = '/api/userHasProject';

		$bodyParameters = [];
		$bodyParameters['user_id'] = $user_id;
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['user_role_id'] = $user_role_id;

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

		$response = new UserHasProjectResponse(
			$this->apiClient, 
			new UserHasProject(
				$this->apiClient, 
				$requestBody['data']['user_id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['user_role_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['user']) && !is_null($requestBody['data']['user'])) ? (new UserResponse(
					$this->apiClient, 
					new User(
						$this->apiClient, 
						$requestBody['data']['user']['data']['id'], 
						$requestBody['data']['user']['data']['user_group_id'], 
						$requestBody['data']['user']['data']['name'], 
						$requestBody['data']['user']['data']['email'], 
						(isset($requestBody['data']['user']['data']['password']) ? $requestBody['data']['user']['data']['password'] : null), 
						$requestBody['data']['user']['data']['preferred_language'], 
						$requestBody['data']['user']['data']['created_at'], 
						$requestBody['data']['user']['data']['updated_at']
					)
				)) : null), 
				((isset($requestBody['data']['project']) && !is_null($requestBody['data']['project'])) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['public_key'], 
						$requestBody['data']['project']['data']['last_run_at'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at']
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Get specified relationship between a user and a project
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $userId User UUID
	 * @param string $projectId Project UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return UserHasProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($userId, $projectId, $include = null)
	{
		$routePath = '/api/userHasProject/{userId},{projectId}';

		$pathReplacements = [
			'{userId}' => $userId,
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($include)) {
			$queryParameters['include'] = $include;
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

		$response = new UserHasProjectResponse(
			$this->apiClient, 
			new UserHasProject(
				$this->apiClient, 
				$requestBody['data']['user_id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['user_role_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['user']) && !is_null($requestBody['data']['user'])) ? (new UserResponse(
					$this->apiClient, 
					new User(
						$this->apiClient, 
						$requestBody['data']['user']['data']['id'], 
						$requestBody['data']['user']['data']['user_group_id'], 
						$requestBody['data']['user']['data']['name'], 
						$requestBody['data']['user']['data']['email'], 
						(isset($requestBody['data']['user']['data']['password']) ? $requestBody['data']['user']['data']['password'] : null), 
						$requestBody['data']['user']['data']['preferred_language'], 
						$requestBody['data']['user']['data']['created_at'], 
						$requestBody['data']['user']['data']['updated_at']
					)
				)) : null), 
				((isset($requestBody['data']['project']) && !is_null($requestBody['data']['project'])) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['public_key'], 
						$requestBody['data']['project']['data']['last_run_at'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at']
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified relationship between a user and a project
	 * 
	 * <aside class="notice">Only one relationship per user/project is allowed and only one user can be <code>Owner</code>of a project.</aside>
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $userId User UUID
	 * @param string $projectId Project UUID
	 * @param string $user_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $user_role_id
	 * 
	 * @return UserHasProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($userId, $projectId, $user_id, $project_id, $user_role_id)
	{
		$routePath = '/api/userHasProject/{userId},{projectId}';

		$pathReplacements = [
			'{userId}' => $userId,
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['user_id'] = $user_id;
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['user_role_id'] = $user_role_id;

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

		$response = new UserHasProjectResponse(
			$this->apiClient, 
			new UserHasProject(
				$this->apiClient, 
				$requestBody['data']['user_id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['user_role_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['user']) && !is_null($requestBody['data']['user'])) ? (new UserResponse(
					$this->apiClient, 
					new User(
						$this->apiClient, 
						$requestBody['data']['user']['data']['id'], 
						$requestBody['data']['user']['data']['user_group_id'], 
						$requestBody['data']['user']['data']['name'], 
						$requestBody['data']['user']['data']['email'], 
						(isset($requestBody['data']['user']['data']['password']) ? $requestBody['data']['user']['data']['password'] : null), 
						$requestBody['data']['user']['data']['preferred_language'], 
						$requestBody['data']['user']['data']['created_at'], 
						$requestBody['data']['user']['data']['updated_at']
					)
				)) : null), 
				((isset($requestBody['data']['project']) && !is_null($requestBody['data']['project'])) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['public_key'], 
						$requestBody['data']['project']['data']['last_run_at'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at']
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified relationship between a user and a project
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $userId User UUID
	 * @param string $projectId Project UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($userId, $projectId)
	{
		$routePath = '/api/userHasProject/{userId},{projectId}';

		$pathReplacements = [
			'{userId}' => $userId,
			'{projectId}' => $projectId,
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
