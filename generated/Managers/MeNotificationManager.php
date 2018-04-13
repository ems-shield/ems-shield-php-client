<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\NotificationListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\NoContentResponse;
use EmsShield\Api\Resources\NotificationResponse;
use EmsShield\Api\Resources\Notification;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * MeNotification manager class
 * 
 * @package EmsShield\Api\Managers
 */
class MeNotificationManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * MeNotification manager class constructor
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
	 * Current user notification list
	 * 
	 * You can specify a GET parameter `read_status` to filter results.
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $read_status
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return NotificationListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($read_status = null, $include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/me/notification';

		$queryParameters = [];

		if (!is_null($read_status)) {
			$queryParameters['read_status'] = $read_status;
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

		$response = new NotificationListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Notification(
					$this->apiClient, 
					$data['id'], 
					$data['type'], 
					$data['notifiable_id'], 
					$data['notifiable_type'], 
					$data['data'], 
					$data['read_at'], 
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
	 * Mark all user notifications as read
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return NoContentResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function markAllAsRead()
	{
		$routeUrl = '/api/me/notification/all/read';

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

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

		$response = new NoContentResponse(
			$this->apiClient
		);

		return $response;
	}
	
	/**
	 * Mark the specified user notification as read
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $notificationId Notification UUID
	 * 
	 * @return NotificationResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function markAsRead($notificationId)
	{
		$routePath = '/api/me/notification/{notificationId}/read';

		$pathReplacements = [
			'{notificationId}' => $notificationId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

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

		$response = new NotificationResponse(
			$this->apiClient, 
			new Notification(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['type'], 
				$requestBody['data']['notifiable_id'], 
				$requestBody['data']['notifiable_type'], 
				$requestBody['data']['data'], 
				$requestBody['data']['read_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Mark the specified user notification as unread
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $notificationId Notification UUID
	 * 
	 * @return NotificationResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function markAsUnread($notificationId)
	{
		$routePath = '/api/me/notification/{notificationId}/unread';

		$pathReplacements = [
			'{notificationId}' => $notificationId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

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

		$response = new NotificationResponse(
			$this->apiClient, 
			new Notification(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['type'], 
				$requestBody['data']['notifiable_id'], 
				$requestBody['data']['notifiable_type'], 
				$requestBody['data']['data'], 
				$requestBody['data']['read_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
}
