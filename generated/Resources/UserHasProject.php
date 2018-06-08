<?php

namespace EmsShield\Api\Resources;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;

/**
 * UserHasProject resource class
 * 
 * @package EmsShield\Api\Resources
 */
class UserHasProject 
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
	public $user_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $project_id;

	/**
	 * @var string
	 */
	public $user_role_id;

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
	 * @var UserResponse
	 */
	public $user;

	/**
	 * @var ProjectResponse
	 */
	public $project;

	/**
	 * UserHasProject resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $user_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $user_role_id
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param UserResponse $user
	 * @param ProjectResponse $project
	 */
	public function __construct(ApiClient $apiClient, $user_id = null, $project_id = null, $user_role_id = null, $created_at = null, $updated_at = null, $user = null, $project = null)
	{
		$this->apiClient = $apiClient;
		$this->user_id = $user_id;
		$this->project_id = $project_id;
		$this->user_role_id = $user_role_id;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->user = $user;
		$this->project = $project;
	}
	/**
	 * Update a specified relationship between a user and a project
	 * 
	 * <aside class="notice">Only one relationship per user/project is allowed and only one user can be <code>Owner</code>of a project.</aside>
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $user_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $user_role_id
	 * 
	 * @return UserHasProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($user_id, $project_id, $user_role_id)
	{
		$routePath = '/api/userHasProject/{userId},{projectId}';

		$pathReplacements = [
			'{userId}' => $this->user,
			'{projectId}' => $this->project,
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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/userHasProject/{userId},{projectId}';

		$pathReplacements = [
			'{userId}' => $this->user,
			'{projectId}' => $this->project,
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
