<?php
class component_routing_dispatcher {
	/**
	 * @var string $basepath
	 */
	protected $basepath;

	/**
	 * @var frontend_model_template|backend_model_template $template
	 * @var component_httpUtils_header $header
	 * @var component_core_language $language
	 * @var component_collections_plugins $pluginsCollection
	 * @var component_collections_setting $settingCollection
	 * @var form_inputEscape $formClean
	 * @var debug_logger $logger
	 */
    protected
		$template,
		$header,
		$language,
		$pluginsCollection,
		$settingCollection,
		$formClean,
		$logger;

	/**
	 * @var string $pathadmin
	 */
    protected $pathadmin;

	/**
	 * @var array $controllerCollection
	 */
    protected $controllerCollection;

	/**
	 * @var string $access
	 * @var string $router
	 * @var string $controller
	 * @var string $controller_name
	 * @var string $plugins
	 * @var string $action
	 * @var string $http_error
	 */
    public
		$access,
		$router,
		$controller,
		$controller_name,
		$plugins,
		$action,
		$http_error;

	/**
	 * component_routing_dispatcher constructor.
	 * @param string(frontend|backend) $access
	 */
    public function __construct($access){
    	$this->access = $access;
    	$this->basepath = component_core_system::basePath();
        $this->formClean = new form_inputEscape();
		$this->setRoutes();
		$this->pluginsCollection = new component_collections_plugins();
		$this->settingCollection = new component_collections_setting();

		if(http_request::isGet('action')) $this->action = $this->formClean->simpleClean($_GET['action']);
        if(http_request::isGet('http_error')) $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);

        if(defined('PATHADMIN')) $this->pathadmin = $this->basepath . PATHADMIN . DIRECTORY_SEPARATOR;

        $this->dispatch();
    }

	/**
	 * Define routes
	 * @param string(frontend|backend) $access
	 */
    private function setRoutes() {
		$this->router = $this->access;
		$this->plugins = null;
    	$model = $this->access.'_model_template';
		$this->template = new $model;
		$this->language = new component_core_language($this->template);
		$this->header = new component_httpUtils_header($this->template);
		if(http_request::isGet('controller')) $this->controller_name = $this->formClean->simpleClean($_GET['controller']);

		if($this->access === 'frontend') {
			$this->controller_name = isset($this->controller_name) ? $this->controller_name : 'home';
			$this->controllerCollection = ['home','about','pages','news','catalog','cookie','webservice','service'];

			if(!in_array($this->controller_name,$this->controllerCollection)){
				$this->router = 'plugins';
				$this->plugins = 'public';
				$pluginsSetConfig = new frontend_model_plugins($this->template);
				$pluginsSetConfig->addConfigDir($this->router);
			}
		}
		elseif($this->access === 'backend') {
			$file_finder = new file_finder();
			$controllerFinder = $file_finder->scanDir($this->basepath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.'controller');
			$funcBasenameFinder = function($value) {
				return basename($value,'.php');
			};
			$this->controllerCollection = array_map($funcBasenameFinder,$controllerFinder);
			$members = new backend_controller_login($this->template);

			if(!$this->controller_name)
			{
				$members->checkout();
			}
			else {
				if(in_array($this->controller_name,$this->controllerCollection)){
					if($this->controller_name !== 'login'){
						$members->checkout();

						if (http_request::isSession('keyuniqid_admin')) {
							$members->getAdminProfile();
						}
					}
				}
				else {
					$this->router = 'plugins';
					$this->plugins = 'admin';
					$members->checkout();

					if (http_request::isSession('keyuniqid_admin')) {
						$members->getAdminProfile();
						$pluginsSetConfig = new backend_model_plugins($this->template);
						$pluginsSetConfig->addConfigDir($this->router);
						$pluginsSetConfig->templateDir($this->router, $this->plugins);
					}
				}
			}
		}

		$this->controller = $this->router.'_controller_';
	}

    /**
     * @return mixed
     */
    private function pluginsRegister(){
        $pluginsCheck =  $this->pluginsCollection->fetch(array('context'=>'check','name'=>$this->controller_name));
        return $pluginsCheck['name'];
    }

	/**
	 * Preload components
	 * @param $lang
	 */
	private function preloadComponents($lang)
	{
		$this->template->assign('setting', $this->template->settings);

		if ($this->router === 'frontend' || ($this->router === 'plugins' && $this->plugins === 'public')) {
			$this->template->assign('theme',$this->template->theme);
			$this->template->assign('domain',$this->template->domain);
			$this->template->assign('dataLang',$this->template->langs);
			$this->template->assign('defaultLang',$this->template->defaultLang);
            $this->template->assign('defaultDomain',$this->template->defaultDomain);
            $modelLogo = new frontend_model_logo($this->template);
            $this->template->assign('logo', $modelLogo->getLogoData());
            $this->template->assign('favicon', $modelLogo->getFaviconData());
            $this->template->assign('social', $modelLogo->getImageSocial());
            $this->template->assign('homescreen', $modelLogo->getHomescreen());
			$modelAbout = new frontend_model_about($this->template);
			$this->template->assign('about', $modelAbout->getContentData());
			$this->template->assign('companyData', $modelAbout->getCompanyData());
			$modelMenu = new frontend_model_menu($this->template);
			$modelMenu->setLinksData($lang);
			$modelBread = new frontend_model_breadcrumb($this->template);
			$modelBread->getBreadcrumb($lang);
			$modelShare = new frontend_model_share($this->template);
			$this->template->assign('shareUrls',$modelShare->getShareUrl());
			$this->template->assign('shareConfig',$modelShare->getShareConfig());
		}
    }

	/**
	 * @param $error
	 */
	private function getError($error)
	{
		$this->template->assign('getTitleHeader', $this->header->getTitleHeader($error), true);
		$this->template->assign('getTxtHeader', $this->header->getTxtHeader($error), true);
		$this->template->assign('error_code', $error, true);
		$this->template->display('error/index.tpl');
    }

    /**
     * @return void|stdClass
     */
    private function getController(){
    	$this->logger = new debug_logger(MP_LOG_DIR);
		$controller_class = '';

        switch($this->router) {
			case 'frontend':
				$controller_class = $this->controller . ($this->controller_name ? $this->controller_name : 'home');
				break;
			case 'backend':
				$controller_class = $this->controller . $this->controller_name;
				break;
			case 'plugins':
				if (isset($this->plugins) && $this->plugins !== null) {
					$pluginLoadFiles = ['public', 'admin'];
					if (in_array($this->plugins, $pluginLoadFiles)) {
						$pluginsDir = component_core_system::basePath() . 'plugins' . DIRECTORY_SEPARATOR . $this->controller_name;
						if($this->plugins === 'admin') {
							$pluginActions = ['setup','upgrade','translate','uninstall'];
							if(in_array($this->action,$pluginActions) && class_exists('backend_controller_plugins')) {
								$pluginsController = new backend_controller_plugins();

								switch ($this->action) {
									case 'setup':
										$pluginsController->register($this->controller_name);
										break;
									case 'upgrade':
										$pluginsController->upgrade($this->controller_name);
										break;
									case 'translate':
										$pluginsController->translate($this->controller_name);
										break;
									case 'uninstall':
										$pluginsController->unregister($this->controller_name);
										break;
								}
								return;
							}
						}

						if ($this->pluginsRegister() !== null && file_exists($pluginsDir)) {
							$controller_class = $this->router . '_' . $this->controller_name . '_' . $this->plugins;
						}
						else {
                            $this->preloadComponents($this->template->lang);
							$this->getError(404);
						}
					}
					else {
						$this->logger->log('php', 'error', 'An error has occured : ' . $this->router . ' ' . $this->controller_name, debug_logger::LOG_MONTH);
					}
				}
				break;
		}

		if($this->access === 'backend') $this->template->assign('cClass',$controller_class);
		if($controller_class && class_exists($controller_class)) {
			try {
				$class =  new $controller_class($this->template);
				if ($class instanceof $controller_class) {
					return $class;
				}
				else {
					throw new Exception('Fail to instantiate the class: : '.$controller_class);
				}
			}
			catch(Exception $e) {
				$this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
			}
        }
		else {
			$this->logger->log('php', 'error', 'An error has occured : controller '.$this->controller_name.' not found in '.$this->router, debug_logger::LOG_MONTH);
		}
    }

    /**
     * Execute dispatch
     */
    public function dispatch(){
		$this->template->configLoad();
		$this->header->mobileDetect();
		$this->template->assign('url',http_url::getUrl());
		$lang = $this->template->lang;
		$this->template->assign('lang',$lang);

		$adminSession = false;
		if (isset($_COOKIE['mc_admin'])) {
			$sql = 'SELECT mas.id_admin_session,mas.id_admin,mae.pseudo_admin,maar.id_role
				FROM mc_admin_session mas
				JOIN mc_admin_employee mae ON (mas.keyuniqid_admin = mae.keyuniqid_admin)
				JOIN mc_admin_access_rel maar ON (mas.id_admin = maar.id_admin)
				WHERE id_admin_session = :id_admin_session';

			$session = component_routing_db::layer()->fetch($sql, ['id_admin_session'=>$_COOKIE['mc_admin']]);

			if( !empty($session) ){
				$adminSession = true;
			}
		}

		if(
			($this->router === 'frontend' || ($this->router === 'plugins' && $this->plugins === 'public'))
			&& $this->template->settings['maintenance']['value'] === '1'
			&& !$adminSession) {
			$this->template->assign('theme',$this->template->theme);
			$this->template->assign('domain',$this->template->domain);
			$this->template->assign('dataLang',$this->template->langs);
			$this->template->assign('defaultLang',$this->template->defaultLang);
			$this->template->assign('defaultDomain',$this->template->defaultDomain);
			$modelLogo = new frontend_model_logo($this->template);
			$this->template->assign('logo', $modelLogo->getLogoData());
			$this->template->assign('favicon', $modelLogo->getFaviconData());
			$this->template->assign('social', $modelLogo->getImageSocial());
			$this->template->assign('homescreen', $modelLogo->getHomescreen());
			$this->header->set_503_header();
			$this->template->display('maintenance.tpl');
		}
		else {
			$this->preloadComponents($lang);

			if($this->http_error) {
				$this->getError($this->http_error);
			}
			else {
				$dispatcher = $this->getController();
				if(gettype($dispatcher) === 'object' && method_exists($dispatcher,'run')) $dispatcher->run();
			}
		}
    }
}