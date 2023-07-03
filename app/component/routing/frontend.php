<?php
class component_routing_frontend extends component_routing_dispatcher {
	/**
	 * Define routes
     * @return component_routing_frontend
	 */
    public function setRoutes(): component_routing_frontend {
		$this->router = 'frontend';
		$this->template = new frontend_model_template();
		$this->language = new component_core_language($this->template);
		$this->header = new component_httpUtils_header($this->template);
		if(http_request::isGet('controller')) $this->controller_name = form_inputEscape::simpleClean($_GET['controller']);
        $this->controller_name = $this->controller_name ?? 'home';
        $this->controllerCollection = ['home','about','pages','news','catalog','cookie','webservice','service'];
        if(!in_array($this->controller_name,$this->controllerCollection)) $this->router = 'plugins';
        return $this;
	}

	/**
	 * Preload components
	 * @param string $lang
	 * @param bool $maintenance
	 */
    protected function preloadComponents(string $lang, bool $maintenance = false) {
		$this->template->assign('setting', $this->template->settings);
        $this->template->assign('theme',$this->template->theme);
        $this->template->assign('domain',$this->template->domain);
        $this->template->assign('dataLang',$this->template->langs);
        $this->template->assign('defaultLang',$this->template->defaultLang);
        $this->template->assign('defaultDomain',$this->template->defaultDomain);
        $this->template->assign('logo', $this->template->logo['logo']);
        $this->template->assign('favicon', $this->template->logo['favicon']);
        $this->template->assign('social', $this->template->logo['social']);
        $this->template->assign('homescreen', $this->template->logo['homescreen']);

        if(!$maintenance) {
            if(!isset($this->controller_name)) $this->template->breadcrumb->addItem($this->template->getConfigVars('home'));
            else $this->template->breadcrumb->addItem($this->template->getConfigVars('home'),'/'.$this->template->lang.($this->template->is_amp() ? '/amp/' : '/'),$this->template->getConfigVars('home'));
            $this->template->assign('shareUrls',$this->template->share['urls']);
            $this->template->assign('shareConfig',$this->template->share['config']);
            $this->template->assign('companyData', $this->template->companyData);
            $this->template->assign('about', $this->template->aboutModel->getContentData());
            $modelMenu = new frontend_model_menu($this->template);
            $modelMenu->setLinksData($lang);
        }
    }

    /**
     * @return string
     */
    protected function getController(): string {
    	$this->logger = new debug_logger(MP_LOG_DIR);
		$controller_class = '';

        switch($this->router) {
			case 'frontend':
				$controller_class = 'frontend_controller_'.($this->controller_name ?: 'home');
				break;
            case 'plugins':
                $pluginsDir = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$this->controller_name;
                if ($this->pluginsRegister() && file_exists($pluginsDir)) {
                    $controller_class = 'plugins_'.$this->controller_name.'_public';
                    $pluginsSetConfig = new frontend_model_plugins($this->template);
                    $pluginsSetConfig->addConfigDir('plugins');
                }
				break;
		}

		return $controller_class;
    }

    /**
     * Execute dispatch
     */
    public function dispatch() {
        parent::dispatch();

		$adminSession = false;
		if (isset($_COOKIE['mc_admin'])) {
			$sql = 'SELECT mas.id_admin
				FROM mc_admin_session mas
				JOIN mc_admin_employee mae ON (mas.keyuniqid_admin = mae.keyuniqid_admin)
				WHERE id_admin_session = :id_admin_session';
            try {
                $session = component_routing_db::layer()->fetch($sql, ['id_admin_session' => $_COOKIE['mc_admin']]);
            } catch (Exception $e) {
                if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
            }
			if(!empty($session) ) $adminSession = true;
		}
		if (isset($_COOKIE['consentedCookies'])) $this->template->assign('consentedCookies',(array)json_decode($_COOKIE['consentedCookies']));

		if($this->template->settings['maintenance'] === '1' && !$adminSession) {
            $this->preloadComponents($this->template->lang,true);
			$this->header->set_503_header();
            $this->template->configLoad();
			$this->template->display('maintenance.tpl');
		}
		else {
			$this->preloadComponents($this->template->lang);

			if(isset($this->http_error)) {
                $this->preloadComponents($this->template->lang);
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
}