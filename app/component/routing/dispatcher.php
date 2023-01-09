<?php
abstract class component_routing_dispatcher {
	/**
	 * @var frontend_model_template|backend_model_template $template
	 */
	protected $template;

    /**
     * @var component_httpUtils_header $header
     */
	protected component_httpUtils_header $header;

    /**
     * @var component_core_language $language
     */
	protected component_core_language $language;

    /**
     * @var component_collections_plugins $pluginsCollection
     */
	protected component_collections_plugins $pluginsCollection;

    /**
     * @var debug_logger $logger
     */
	protected debug_logger $logger;

	/**
	 * @var array $controllerCollection
	 */
    protected array $controllerCollection;

	/**
	 * @var string $access
	 * @var string $router
	 * @var string $controller
	 * @var string $controller_name
	 * @var string $plugins
	 * @var string $action
	 * @var string $http_error
	 */
    public string
		$router,
		$controller,
		$controller_name,
		$plugins,
		$action,
		$http_error;

    /**
     * @return component_routing_dispatcher
     */
    public function __construct() {
		if(http_request::isGet('action')) $this->action = form_inputEscape::simpleClean($_GET['action']);
        if(http_request::isGet('http_error')) $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);
        return $this;
    }

    /**
     * @param int $error
     */
    protected function getError(int $error) {
        $this->template->assign('getTitleHeader', $this->header->getTitleHeader($error), true);
        $this->template->assign('getTxtHeader', $this->header->getTxtHeader($error), true);
        $this->template->assign('error_code', $error, true);
        $this->template->display('error/index.tpl');
    }

    /**
     * @return bool
     */
    protected function pluginsRegister(): bool {
        $this->pluginsCollection = new component_collections_plugins();
        $pluginsCheck =  $this->pluginsCollection->fetch(['context' => 'check','name' => $this->controller_name]);
        return !empty($pluginsCheck);
    }

    /**
     * @param string $controller_class
     * @return bool
     */
    protected function loadController(string $controller_class): bool {
        if(!empty($controller_class) && class_exists($controller_class)) {
            try {
                $controller =  new $controller_class();
                if ($controller instanceof $controller_class && method_exists($controller,'run')) {
                    $this->template->configLoad();
                    $controller->run();
                    return true;
                }
            }
            catch(Exception $e) {
                $this->logger->log('php', 'error', 'An error has occured : Fail to instantiate the class: : '.$controller_class.' -> '.$e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
        else {
            $this->logger->log('php', 'error', 'An error has occured : controller '.$this->controller_name.' not found in '.$this->router, debug_logger::LOG_MONTH);
        }
        return false;
    }

	/**
	 * Preload components
	 * @param string $lang
	 * @param bool $maintenance
	 */
    abstract protected function preloadComponents(string $lang, bool $maintenance = false);

    /**
     * @return bool|object
     */
    abstract protected function getController();

    /**
     * Define routes
     * @return component_routing_frontend|component_routing_backend
     */
    abstract public function setRoutes();

    /**
     * Execute dispatch
     */
    public function dispatch() {
        $this->header->mobileDetect();
        $this->template->assign('url',http_url::getUrl());
        $this->template->assign('lang',$this->template->lang);
    }
}