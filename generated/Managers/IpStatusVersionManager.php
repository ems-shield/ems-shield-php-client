<?php

namespace EmsShield\Api\Managers;

use EmsShield\Api\ApiClient;
use EmsShield\Api\Exceptions\UnexpectedResponseException;
use EmsShield\Api\Resources\IpStatusVersionListResponse;
use EmsShield\Api\Resources\ErrorResponse;
use EmsShield\Api\Resources\IpStatusVersionResponse;
use EmsShield\Api\Resources\IpStatusVersion;
use EmsShield\Api\Resources\Meta;
use EmsShield\Api\Resources\Pagination;

/**
 * IpStatusVersion manager class
 * 
 * @package EmsShield\Api\Managers
 */
class IpStatusVersionManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * IpStatusVersion manager class constructor
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
	 * Show ip status version list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return IpStatusVersionListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/ipStatusVersion';

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

		$response = new IpStatusVersionListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new IpStatusVersion(
					$this->apiClient, 
					$data['ip_status_id'], 
					$data['i18n_lang_id'], 
					$data['description'], 
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
	 * Create and store a new ip status version
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $ip_status_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return IpStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($ip_status_id, $i18n_lang_id, $description)
	{
		$routeUrl = '/api/ipStatusVersion';

		$bodyParameters = [];
		$bodyParameters['ip_status_id'] = $ip_status_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;
		$bodyParameters['description'] = $description;

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

		$response = new IpStatusVersionResponse(
			$this->apiClient, 
			new IpStatusVersion(
				$this->apiClient, 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified ip status version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $ipStatusId Ip Status ID
	 * @param string $i18nLangId I18n Lang ID
	 * 
	 * @return IpStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($ipStatusId, $i18nLangId)
	{
		$routePath = '/api/ipStatusVersion/{ipStatusId},{i18nLangId}';

		$pathReplacements = [
			'{ipStatusId}' => $ipStatusId,
			'{i18nLangId}' => $i18nLangId,
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

		$response = new IpStatusVersionResponse(
			$this->apiClient, 
			new IpStatusVersion(
				$this->apiClient, 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified ip status version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $ipStatusId Ip Status ID
	 * @param string $i18nLangId I18n Lang ID
	 * @param string $ip_status_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return IpStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($ipStatusId, $i18nLangId, $ip_status_id, $i18n_lang_id, $description)
	{
		$routePath = '/api/ipStatusVersion/{ipStatusId},{i18nLangId}';

		$pathReplacements = [
			'{ipStatusId}' => $ipStatusId,
			'{i18nLangId}' => $i18nLangId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['ip_status_id'] = $ip_status_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;
		$bodyParameters['description'] = $description;

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

		$response = new IpStatusVersionResponse(
			$this->apiClient, 
			new IpStatusVersion(
				$this->apiClient, 
				$requestBody['data']['ip_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified ip status version
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $ipStatusId Ip Status ID
	 * @param string $i18nLangId I18n Lang ID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($ipStatusId, $i18nLangId)
	{
		$routePath = '/api/ipStatusVersion/{ipStatusId},{i18nLangId}';

		$pathReplacements = [
			'{ipStatusId}' => $ipStatusId,
			'{i18nLangId}' => $i18nLangId,
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
