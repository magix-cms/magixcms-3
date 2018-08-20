<?php
class component_routing_dispatcher{
    protected $header,$template,$pluginsCollection,$settingCollection,$pathadmin;
    /**
     * @var Dispatcher
     */
    public $router,$controller,$controller_name,$plugins,$action;

    public function __construct($router,$template,$plugins = null){
        $formClean = new form_inputEscape();
        $this->router = $router;
        $this->controller = $router.'_controller_';
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        if(http_request::isGet('action')){
            $this->action = $formClean->simpleClean($_GET['action']);
        }
        if(http_request::isGet('http_error')){
            $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);
        }
        $this->plugins = $plugins;
        $this->header = new component_httpUtils_header($template);
        $this->template = $template;
        $this->pluginsCollection = new component_collections_plugins();
        $this->settingCollection = new component_collections_setting();
        $this->pathadmin = component_core_system::basePath().PATHADMIN.DIRECTORY_SEPARATOR;
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
        $data = $this->settingCollection->fetchData(array('context'=>'all','type'=>'setting'));
        $arr = array();
        if($data != null) {
            foreach ($data as $item) {
                $arr[$item['name']] = array();
                $arr[$item['name']]['value'] = $item['value'];
                $arr[$item['name']]['category'] = $item['category'];
            }
            $this->template->assign('setting', $arr);

            /*if($arr['fav']['value'] !== '') {
            	$faDir = $this->pathadmin.'template'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'fontawesome'.DIRECTORY_SEPARATOR.$arr['fav']['value'].DIRECTORY_SEPARATOR;
            	if(!file_exists($faDir)){
					$content = file_get_contents('https://maxcdn.bootstrapcdn.com/font-awesome/'.$arr['fav']['value'].'/fonts/fontawesome-webfont.ttf');

					if ($content !== false) {
						$makeFiles = new filesystem_makefile();
						$makeFiles->mkdir($faDir);
						file_put_contents($faDir.'fontawesome-webfont.ttf', $content);
					}
				}
			}*/
        }
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
	 * @param $currentIso
	 */
	private function domainAndLangs($currentIso)
	{
		$collectionsLang = new component_collections_language();
		$collectionDomain =  new frontend_db_domain();

		$currentDomain = $collectionDomain->fetchData(array('context'=>'one','type'=>'currentDomain'),array('url'=>$_SERVER['HTTP_HOST']));
		if($currentDomain['id_domain'] != null && isset($_SERVER['HTTP_HOST'])) {
			$this->template->assign('domainData',$currentDomain);
			$domain = $collectionDomain->fetchData(array('context' => 'all', 'type' => 'languages'), array('id' => $currentDomain['id_domain']));

			if($domain != null){
				$data = $domain;
				if (!$currentIso) $default = $collectionDomain->fetchData(array('context'=>'one','type'=>'language'),array('id' => $currentDomain['id_domain']));
			}
		}
		$this->template->assign('defaultLang',$default ? $default : $collectionsLang->fetchData(array('context'=>'one','type'=>'default')));
		$this->template->assign('dataLang',$data ? $data : $collectionsLang->fetchData(array('context'=>'all','type'=>'active')));
    }

	/**
	 * Preload components
	 */
	private function preloadComponents()
	{
		$this->template->configLoad();
		$this->header->mobileDetect();
		$this->template->assign('url',http_url::getUrl());
		$lang = $this->template->currentLanguage();
		$this->template->assign('lang',$lang);
		$this->getSetting();
		//$this->getCssInliner();

		if ($this->router === 'frontend' || ($this->router === 'plugins' && $this->plugins === 'public')) {
			$this->template->assign('theme',$this->template->theme);
			$this->domainAndLangs($lang);
			$modelAbout = new frontend_model_about($this->template);
			$this->template->assign('about', $modelAbout->getContentData());
			$this->template->assign('companyData', $modelAbout->getCompanyData());
			$modelMenu = new frontend_model_menu($this->template);
			$modelMenu->setLinksData($lang);
		}
    }

    /**
     * @return mixed
     */
    private function getController(){
        switch($this->router) {
			case 'frontend':
				if ($this->controller_name) {
					$controller_class = $this->controller . $this->controller_name;
				} else {
					$controller_class = $this->controller . 'home';
				}
				break;
			case 'backend':
				$controller_class = $this->controller . $this->controller_name;
				$this->template->assign('cClass',$controller_class);
				break;
			case 'plugins':
				if (isset($this->plugins) && $this->plugins != null) {
					$pluginLoadFiles = array('public', 'admin');
					if (in_array($this->plugins, $pluginLoadFiles)) {
						$pluginsDir = component_core_system::basePath() . 'plugins' . DIRECTORY_SEPARATOR . $this->controller_name;
						if ($this->plugins !== 'admin') {
							if ($this->pluginsRegister() != null && file_exists($pluginsDir)) {
								$controller_class = $this->router . '_' . $this->controller_name . '_' . $this->plugins;
							} else {
								$this->template->assign(
									'getTitleHeader',
									$this->header->getTitleHeader(
										404
									),
									true
								);
								$this->template->assign(
									'getTxtHeader',
									$this->header->getTxtHeader(
										404
									),
									true
								);
								$this->template->assign('error_code', '404', true);
								$this->header->mobileDetect();
								$this->template->display('error/index.tpl');
							}
						}
						else {
							if ($this->action === 'setup') {
								if (class_exists('backend_controller_plugins')) {
									$pluginsController = new backend_controller_plugins();
									$pluginsController->register($this->controller_name);
								}
							} elseif ($this->action === 'upgrade') {
								if (class_exists('backend_controller_plugins')) {
									$pluginsController = new backend_controller_plugins();
									$pluginsController->upgrade($this->controller_name);
								}
							} elseif ($this->action === 'translate') {
								if (class_exists('backend_controller_plugins')) {
									$pluginsController = new backend_controller_plugins();
									$pluginsController->translate($this->controller_name);
								}
							} else {
								if ($this->pluginsRegister() != null && file_exists($pluginsDir)) {
									$controller_class = $this->router . '_' . $this->controller_name . '_' . $this->plugins;
									$this->template->assign('cClass',$controller_class);
								} else {
									$this->template->assign(
										'getTitleHeader',
										$this->header->getTitleHeader(
											404
										),
										true
									);
									$this->template->assign(
										'getTxtHeader',
										$this->header->getTxtHeader(
											404
										),
										true
									);
									$this->template->assign('error_code', '404', true);
									$this->header->mobileDetect();
									$this->template->display('error/index.tpl');
								}
							}
						}
					} else {
						$logger = new debug_logger(MP_LOG_DIR);
						$logger->log('php', 'error', 'An error has occured : ' . $this->router . ' ' . $this->controller_name, debug_logger::LOG_MONTH);
						//trigger_error('An error has occured : '.$this->router. ' ' . $this->controller_name, E_USER_WARNING);
					}
				}
				break;
		}

        try{
            if(class_exists($controller_class)) {
                $class =  new $controller_class($this->template);
                if ($class instanceof $controller_class) {
                    return $class;
                } else {
                    //throw new Exception('not instantiate the class: ' . $controller_class);
                    $logger = new debug_logger(MP_LOG_DIR);
                    $logger->log('php', 'error', 'Not instantiate the class: : '.$controller_class , debug_logger::LOG_MONTH);
                }
            }
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Execute dispatch
     */
    public function dispatch(){
        $dispatcher = $this->getController();
        if($dispatcher){
            if(method_exists($dispatcher,'run')){
                $this->preloadComponents();
                $dispatcher->run();
            }
        }
    }
}