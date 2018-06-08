<?php

namespace EmsShield\Api;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use EmsShield\Api\Managers\MeManager;
use EmsShield\Api\Managers\MeNotificationManager;
use EmsShield\Api\Managers\UserGroupManager;
use EmsShield\Api\Managers\UserManager;
use EmsShield\Api\Managers\I18nLangManager;
use EmsShield\Api\Managers\ProjectManager;
use EmsShield\Api\Managers\UserHasProjectManager;
use EmsShield\Api\Managers\IpStatusManager;
use EmsShield\Api\Managers\IpStatusVersionManager;
use EmsShield\Api\Managers\IpManager;
use EmsShield\Api\Managers\IpLogManager;
use EmsShield\Api\Managers\ProjectTagManager;
use EmsShield\Api\Managers\ProjectTaggableManager;
use EmsShield\Api\Managers\ServerManager;
use EmsShield\Api\Managers\ServerLogManager;
use EmsShield\Api\Managers\DeployTaskManager;

/**
 * ems-shield client class (version 1.0)
 * 
 * @package EmsShield\Api
 */
class ApiClient 
{
	/**
	 * API base url for requests
	 *
	 * @var string
	 */
	protected $apiBaseUrl;

	/**
	 * Guzzle client for API requests
	 *
	 * @var GuzzleClient;
	 */
	protected $httpClient;

	/**
	 * Bearer authentication access token
	 *
	 * @var string
	 */
	protected $bearerToken;

	/**
	 * Map of global headers to use with every requests
	 *
	 * @var string[]
	 */
	protected $globalHeaders = [];

	/**
	 * Me manager
	 *
	 * @var MeManager
	 */
	protected $meManager;

	/**
	 * MeNotification manager
	 *
	 * @var MeNotificationManager
	 */
	protected $meNotificationManager;

	/**
	 * UserGroup manager
	 *
	 * @var UserGroupManager
	 */
	protected $userGroupManager;

	/**
	 * User manager
	 *
	 * @var UserManager
	 */
	protected $userManager;

	/**
	 * I18nLang manager
	 *
	 * @var I18nLangManager
	 */
	protected $i18nLangManager;

	/**
	 * Project manager
	 *
	 * @var ProjectManager
	 */
	protected $projectManager;

	/**
	 * UserHasProject manager
	 *
	 * @var UserHasProjectManager
	 */
	protected $userHasProjectManager;

	/**
	 * IpStatus manager
	 *
	 * @var IpStatusManager
	 */
	protected $ipStatusManager;

	/**
	 * IpStatusVersion manager
	 *
	 * @var IpStatusVersionManager
	 */
	protected $ipStatusVersionManager;

	/**
	 * Ip manager
	 *
	 * @var IpManager
	 */
	protected $ipManager;

	/**
	 * IpLog manager
	 *
	 * @var IpLogManager
	 */
	protected $ipLogManager;

	/**
	 * ProjectTag manager
	 *
	 * @var ProjectTagManager
	 */
	protected $projectTagManager;

	/**
	 * ProjectTaggable manager
	 *
	 * @var ProjectTaggableManager
	 */
	protected $projectTaggableManager;

	/**
	 * Server manager
	 *
	 * @var ServerManager
	 */
	protected $serverManager;

	/**
	 * ServerLog manager
	 *
	 * @var ServerLogManager
	 */
	protected $serverLogManager;

	/**
	 * DeployTask manager
	 *
	 * @var DeployTaskManager
	 */
	protected $deployTaskManager;

	/**
	 * API Client class constructor
	 *
	 * @param string $bearerToken Bearer authentication access token
	 * @param string $apiBaseUrl API base url for requests
	 * @param string[] $globalHeaders Map of global headers to use with every requests
	 * @param mixed[] $guzzleClientConfig Additional Guzzle client configuration
	 */
	public function __construct($bearerToken, $apiBaseUrl = 'https://ems-shield.ryan.ems-dev.net', $globalHeaders = [], $guzzleClientConfig = [])
	{
		$this->apiBaseUrl = $apiBaseUrl;
		$this->globalHeaders = $globalHeaders;

		$this->bearerToken = $bearerToken;

		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());

		$stack->push(Middleware::mapRequest(function (RequestInterface $request) {
			if (count($this->globalHeaders) > 0) {
				$request = $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
				foreach ($this->globalHeaders as $header => $value) {
					$request = $request->withHeader($header, $value);
				}
				return $request;
			} else {
				return $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
			}
		}));
	
		$guzzleClientConfig['handler'] = $stack;
		$guzzleClientConfig['base_uri'] = $apiBaseUrl;

		$this->httpClient = new GuzzleClient($guzzleClientConfig);

		$this->meManager = new MeManager($this);
		$this->meNotificationManager = new MeNotificationManager($this);
		$this->userGroupManager = new UserGroupManager($this);
		$this->userManager = new UserManager($this);
		$this->i18nLangManager = new I18nLangManager($this);
		$this->projectManager = new ProjectManager($this);
		$this->userHasProjectManager = new UserHasProjectManager($this);
		$this->ipStatusManager = new IpStatusManager($this);
		$this->ipStatusVersionManager = new IpStatusVersionManager($this);
		$this->ipManager = new IpManager($this);
		$this->ipLogManager = new IpLogManager($this);
		$this->projectTagManager = new ProjectTagManager($this);
		$this->projectTaggableManager = new ProjectTaggableManager($this);
		$this->serverManager = new ServerManager($this);
		$this->serverLogManager = new ServerLogManager($this);
		$this->deployTaskManager = new DeployTaskManager($this);
	}

	/**
	 * Return the API base url
	 *
	 * @return string
	 */
	public function getApiBaseUrl()
	{
		return $this->apiBaseUrl;
	}

	/**
	 * Return the map of global headers to use with every requests
	 *
	 * @return string[]
	 */
	public function getGlobalHeaders()
	{
		return $this->globalHeaders;
	}

	/**
	 * Return the Guzzle HTTP client
	 *
	 * @return GuzzleClient
	 */
	public function getHttpClient()
	{
		return $this->httpClient;
	}

	/**
	 * Return the Me manager
	 *
	 * @return MeManager
	 */
	public function MeManager()
	{
		return $this->meManager;
	}
	
	/**
	 * Return the MeNotification manager
	 *
	 * @return MeNotificationManager
	 */
	public function MeNotificationManager()
	{
		return $this->meNotificationManager;
	}
	
	/**
	 * Return the UserGroup manager
	 *
	 * @return UserGroupManager
	 */
	public function UserGroupManager()
	{
		return $this->userGroupManager;
	}
	
	/**
	 * Return the User manager
	 *
	 * @return UserManager
	 */
	public function UserManager()
	{
		return $this->userManager;
	}
	
	/**
	 * Return the I18nLang manager
	 *
	 * @return I18nLangManager
	 */
	public function I18nLangManager()
	{
		return $this->i18nLangManager;
	}
	
	/**
	 * Return the Project manager
	 *
	 * @return ProjectManager
	 */
	public function ProjectManager()
	{
		return $this->projectManager;
	}
	
	/**
	 * Return the UserHasProject manager
	 *
	 * @return UserHasProjectManager
	 */
	public function UserHasProjectManager()
	{
		return $this->userHasProjectManager;
	}
	
	/**
	 * Return the IpStatus manager
	 *
	 * @return IpStatusManager
	 */
	public function IpStatusManager()
	{
		return $this->ipStatusManager;
	}
	
	/**
	 * Return the IpStatusVersion manager
	 *
	 * @return IpStatusVersionManager
	 */
	public function IpStatusVersionManager()
	{
		return $this->ipStatusVersionManager;
	}
	
	/**
	 * Return the Ip manager
	 *
	 * @return IpManager
	 */
	public function IpManager()
	{
		return $this->ipManager;
	}
	
	/**
	 * Return the IpLog manager
	 *
	 * @return IpLogManager
	 */
	public function IpLogManager()
	{
		return $this->ipLogManager;
	}
	
	/**
	 * Return the ProjectTag manager
	 *
	 * @return ProjectTagManager
	 */
	public function ProjectTagManager()
	{
		return $this->projectTagManager;
	}
	
	/**
	 * Return the ProjectTaggable manager
	 *
	 * @return ProjectTaggableManager
	 */
	public function ProjectTaggableManager()
	{
		return $this->projectTaggableManager;
	}
	
	/**
	 * Return the Server manager
	 *
	 * @return ServerManager
	 */
	public function ServerManager()
	{
		return $this->serverManager;
	}
	
	/**
	 * Return the ServerLog manager
	 *
	 * @return ServerLogManager
	 */
	public function ServerLogManager()
	{
		return $this->serverLogManager;
	}
	
	/**
	 * Return the DeployTask manager
	 *
	 * @return DeployTaskManager
	 */
	public function DeployTaskManager()
	{
		return $this->deployTaskManager;
	}
}