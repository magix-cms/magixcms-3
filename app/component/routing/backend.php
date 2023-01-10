<?php
class component_routing_backend extends component_routing_dispatcher {
    /**
	 * Define routes
     * @return component_routing_backend
	 */
    public function setRoutes(): component_routing_backend {
        $this->router = 'backend';
		$this->template = new backend_model_template();
		$this->language = new component_core_language($this->template);
		$this->header = new component_httpUtils_header($this->template);
		if(http_request::isGet('controller')) $this->controller_name = form_inputEscape::simpleClean($_GET['controller']);

        $file_finder = new file_finder();
        $controllerFinder = $file_finder->scanDir(component_core_system::basePath().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.'controller');
        $funcBasenameFinder = fn($value) => basename($value,'.php');
        $this->controllerCollection = array_map($funcBasenameFinder,$controllerFinder);
        $members = new backend_controller_login($this->template);

        if(!isset($this->controller_name))  {
            $members->checkout();
        }
        else {
            if($this->controller_name !== 'login'){
                $members->checkout();

                if (http_request::isSession('keyuniqid_admin')) $members->getAdminProfile();
            }
            if(!in_array($this->controller_name,$this->controllerCollection)) {
                $this->router = 'plugins';
                $pluginsSetConfig = new backend_model_plugins($this->template);
                $pluginsSetConfig->addConfigDir($this->router);
                $pluginsSetConfig->templateDir($this->router, 'admin');
            }
        }
        return $this;
	}

	/**
	 * Preload components
	 * @param string $lang
	 * @param bool $maintenance
	 */
	protected function preloadComponents(string $lang, bool $maintenance = false) {
		$this->template->assign('setting', $this->template->settings);
    }

    /**
     * @return string
     */
    protected function getController(): string {
    	$this->logger = new debug_logger(MP_LOG_DIR);
		$controller_class = '';
        switch($this->router) {
			case 'backend':
				$controller_class = 'backend_controller_'.$this->controller_name;
				break;
			case 'plugins':
                $pluginsDir = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$this->controller_name;
                $pluginActions = ['setup','upgrade','translate','uninstall'];
                if(isset($this->action) && in_array($this->action,$pluginActions) && class_exists('backend_controller_plugins')) {
                    try {
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
                    }
                    catch (Exception $e) {
                        if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                        $this->logger->log('error','admin',$e->getMessage(),$this->logger::LOG_MONTH);
                    }
                }
                if($this->pluginsRegister() && file_exists($pluginsDir)) $controller_class = 'plugins_'.$this->controller_name.'_admin';
				break;
		}
		$this->template->assign('cClass',$controller_class);
        return $controller_class;
    }

    /**
     * Execute dispatch
     */
    public function dispatch(){
        parent::dispatch();
        $this->preloadComponents($this->template->lang);

        if(isset($this->http_error)) {
            $this->getError($this->http_error);
        }
        else {
            $controllerName = $this->getController();
            if(!$this->loadController($controllerName)) {
                $this->preloadComponents($this->template->lang);
                $this->getError(404);
            }
        }
    }
}