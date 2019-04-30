<?php
class component_routing_dispatcher{
    protected $header,$template,$pluginsCollection,$settingCollection,$pathadmin;
    /**
     * @var Dispatcher
     */
    public $router,$controller,$controller_name,$plugins,$action,$http_error;

    public function __construct($router,$template,$plugins = null){
        $formClean = new form_inputEscape();
        $this->router = $router;
        $this->controller = $router.'_controller_';

        if(http_request::isGet('controller')) $this->controller_name = $formClean->simpleClean($_GET['controller']);

        if(http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);

        if(http_request::isGet('http_error')) $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);

        $this->plugins = $plugins;
		$this->template = $template;
		$this->header = new component_httpUtils_header($template);
		$this->pluginsCollection = new component_collections_plugins();
        $this->settingCollection = new component_collections_setting();
        if(defined('PATHADMIN')) {
            $this->pathadmin = component_core_system::basePath() . PATHADMIN . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @return mixed
     */
    private function pluginsRegister(){
        $pluginsCheck =  $this->pluginsCollection->fetch(array('context'=>'check','name'=>$this->controller_name));
        return $pluginsCheck['name'];
    }

    /**
     * global assign setting
     */
    private function getSetting(){
        $this->template->assign('setting', $this->settingCollection->getSetting());
    }

    /**
     * global assign css inliner
     */
    private function getCssInliner(){
        $data = $this->settingCollection->fetchData(array('context'=>'all','type'=>'cssInliner'));
        $arr = array();
        if($data != null) {

            foreach ($data as $item) {
                //$arr[$item['property_cssi']] = array();
                $arr[$item['property_cssi']] = $item['color_cssi'];
            }
            $this->template->assign('cssInliner', $arr);
        }
    }

	/**
	 * Preload components
	 * @param $lang
	 */
	private function preloadComponents($lang)
	{
		$this->getSetting();
		//$this->getCssInliner();

		if ($this->router === 'frontend' || ($this->router === 'plugins' && $this->plugins === 'public')) {
			$this->template->assign('theme',$this->template->theme);
			$this->template->assign('domain',$this->template->domain);
			$this->template->assign('dataLang',$this->template->langs);
			$this->template->assign('defaultLang',$this->template->defaultLang);
            $modelLogo = new frontend_model_logo($this->template);
            $this->template->assign('logo', $modelLogo->getLogoData());
            $this->template->assign('favicon', $modelLogo->getFaviconData());
			$modelAbout = new frontend_model_about($this->template);
			$this->template->assign('about', $modelAbout->getContentData());
			$this->template->assign('companyData', $modelAbout->getCompanyData());
			$modelMenu = new frontend_model_menu($this->template);
			$modelMenu->setLinksData($lang);
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
     * @return mixed
     */
    private function getController(){
		$controller_class = '';

        switch($this->router) {
			case 'frontend':
				$controller_class = $this->controller . ($this->controller_name ? $this->controller_name : 'home');
				break;
			case 'backend':
				$controller_class = $this->controller . $this->controller_name;
				break;
			case 'plugins':
				if (isset($this->plugins) && $this->plugins != null) {
					$pluginLoadFiles = array('public', 'admin');
					if (in_array($this->plugins, $pluginLoadFiles)) {
						$pluginsDir = component_core_system::basePath() . 'plugins' . DIRECTORY_SEPARATOR . $this->controller_name;

						if($this->plugins === 'admin') {
							$pluginActions = array('setup','upgrade','translate','uninstall');
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

						if ($this->pluginsRegister() != null && file_exists($pluginsDir)) {
							$controller_class = $this->router . '_' . $this->controller_name . '_' . $this->plugins;
						}
						else {
                            $this->preloadComponents($this->template->lang);
							$this->getError(404);
						}
					}
					else {
						$logger = new debug_logger(MP_LOG_DIR);
						$logger->log('php', 'error', 'An error has occured : ' . $this->router . ' ' . $this->controller_name, debug_logger::LOG_MONTH);
						//trigger_error('An error has occured : '.$this->router. ' ' . $this->controller_name, E_USER_WARNING);
					}
				}
				break;
		}

		if($this->router === 'backend' || ($this->router === 'plugins' && $this->plugins === 'admin')) $this->template->assign('cClass',$controller_class);

        try{
            if($controller_class && class_exists($controller_class)) {
                $class =  new $controller_class($this->template);
                if ($class instanceof $controller_class) {
                    return $class;
                }
                else {
                    //throw new Exception('not instantiate the class: ' . $controller_class);
                    $logger = new debug_logger(MP_LOG_DIR);
                    $logger->log('php', 'error', 'Not instantiate the class: : '.$controller_class , debug_logger::LOG_MONTH);
                }
            }
        }
        catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
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

    	if($this->http_error) {
			$this->getError($this->http_error);
		}
		else {
			$dispatcher = $this->getController();
			if($dispatcher){
				if(method_exists($dispatcher,'run')){
					$this->preloadComponents($lang);
					$dispatcher->run();
				}
			}
		}
    }
}