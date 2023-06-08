<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * MAGIX CMS
 * @category   MODEL 
 * @package    frontend
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien, 
 * http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.0
 * @author Gérits Aurélien <aurelien@magix-cms.com> | <gerits.aurelien@gmail.com>
 * @name frontend_model_template
 *
 */
class frontend_model_template {
	/**
	 * @var frontend_model_template $instance
	 */
	static protected frontend_model_template $instance;

	/**
	 * @var component_collections_setting $collectionsSetting
	 * @var component_collections_language $collectionsLanguage
	 * @var frontend_db_domain $DBDomain
	 */
	protected component_collections_setting $collectionsSetting;
	protected component_collections_language $collectionsLanguage;
	protected frontend_db_domain $DBDomain;
	protected frontend_model_logo $logoModel;
	protected frontend_model_share $shareModel;
    public frontend_model_about $aboutModel;
	public frontend_model_breadcrumb $breadcrumb;

	/**
	 * @var bool $amp
	 */
	protected bool $amp;

	/**
	 * @var string $ConfigFile
	 */
	private string $ConfigFile = 'local_';

    /**
     * @var array $settings
     * @var array $domain
     * @var array $langs
     */
    public array
		$settings,
		$domain,
		$langs,
        $logo,
        $share,
        $companyData;

	/**
	 * @var string $lang
	 * @var string $defaultLang
	 * @var string $defaultDomain
	 * @var string $theme
	 */
	public string
		$lang,
		$defaultLang,
		$defaultDomain,
		$theme;

	/**
	 * @var bool $ssl
	 */
	public bool $ssl;

	/**
	 *
	 */
    public function __construct() {
		if (isset(self::$instance) && self::$instance !== null) {
			foreach (get_object_vars(self::$instance) as $prop=>$value) {
				$this->{$prop} = $value;
			}
		}
		else {
			$this->collectionsSetting = new component_collections_setting();
			$this->collectionsLanguage = new component_collections_language();
			$this->DBDomain = new frontend_db_domain();
            $this->shareModel = new frontend_model_share($this);
            $this->aboutModel = new frontend_model_about($this);

			if(!isset($this->settings)) $this->init();
			self::$instance = $this;
		}
        $this->amp = http_request::isGet('amp') && (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') && $this->ssl;
	}

    private function getCacheData(string $file) {
        $system = json_decode(file_get_contents($file),true);
        $this->settings = $system['settings'];
        $this->ssl = $system['ssl'];
        $this->defaultDomain = $system['defaultDomain'];
        $this->langs = $system['langs'];
        $this->theme = $system['theme'];
        $this->logo = $system['logo'];
        $this->share = $system['share'];
        $this->companyData = $system['companyData'];
    }

	/**
	 * @return void
	 */
    public function init() {
        $systemCacheFile = component_core_system::basePath().'/var/caches/.system';
        if(file_exists($systemCacheFile)) $this->getCacheData($systemCacheFile);
        if(!file_exists($systemCacheFile) || !isset($this->settings) || $this->settings['cache'] !== 'files') {
            $this->settings = $this->collectionsSetting->getSetting();
            $this->ssl = (bool)$this->settings['ssl'];
            $this->langs = $this->languagesAvailable();
            $this->theme = $this->loadTheme();
            $this->defaultDomain = $this->setDefaultDomain();
            $this->share = [
                'urls' => $this->shareModel->getShareUrl(),
                'config' => $this->shareModel->getShareConfig()
            ];
            $this->companyData = $this->aboutModel->getCompanyData();
        }

		$this->domain = [];
		if(isset($_SERVER['HTTP_HOST'])) {
			$current = $this->DBDomain->fetchData(['context' => 'one','type' => 'currentDomain'],['url' => $_SERVER['HTTP_HOST']]);
			$this->domain = $current ?: [];
		}
        $this->lang = $this->currentLanguage();
        $this->breadcrumb = new frontend_model_breadcrumb($this->lang);

        if(!file_exists($systemCacheFile) || $this->settings['cache'] !== 'files') {
            $this->logoModel = new frontend_model_logo($this);
            $this->logo = [
                'logo' => $this->logoModel->getLogoData(),
                'favicon' => $this->logoModel->getFaviconData(),
                'social' => $this->logoModel->getImageSocial(),
                'homescreen' => $this->logoModel->getHomescreen()
            ];

            if(!file_exists($systemCacheFile)) {
                $system = [
                    'settings' => $this->settings,
                    'ssl' => (bool)$this->settings['ssl'],
                    'defaultDomain' => $this->defaultDomain,
                    'langs' => $this->langs,
                    'theme' => $this->settings['theme'],
                    'logo' => $this->logo,
                    'share' => $this->share,
                    'companyData' => $this->companyData
                ];
                $fh = fopen($systemCacheFile, 'x+');
                if(is_writable($systemCacheFile)) {
                    fwrite($fh, json_encode($system) . PHP_EOL);
                    fclose($fh);
                }
            }
        }
	}

	/**
	 * @return bool
	 */
	public function is_amp(): bool {
		return $this->amp;
	}

    /**
     * @return string
     */
    public function setDefaultDomain(): string {
		$defaultDomain = '';
        $data = $this->DBDomain->fetchData(['context' => 'one', 'type' => 'defaultDomain']);
		if(!empty($data)) $defaultDomain = $data['url_domain'];
        return $defaultDomain;
    }

	/**
	 * Get the available languages
	 * @return array
	 */
	public function languagesAvailable(): array {
		$languages = [];
		$languagesData = [];
		if(!empty($this->domain)) {
			$data = $this->DBDomain->fetchData(['context' => 'all', 'type' => 'languages'], ['id' => $this->domain['id_domain']]);
			if(!empty($data)) $languagesData = $data;
		}
		if(!$languagesData) $languagesData = $this->collectionsLanguage->fetchData(['context' => 'all','type' => 'active']);

		foreach ($languagesData as $lang) {
			$languages[$lang['iso_lang']] = $lang;
		}
		return $languages;
    }

    /**
     * Return the default language
	 * @return string
     */
    public function setDefaultLanguage(): string {
    	$lang = '';
        if($this->domain['id_domain'] != null) {
			$data = $this->DBDomain->fetchData(['context' => 'one', 'type' => 'language'], ['id' => $this->domain['id_domain']]);
			if(!empty($data)) $lang = $data['iso_lang'];
		}

        if(!$lang) {
			$data = $this->collectionsLanguage->fetchData(['context' => 'one','type' => 'default']);
			if(!empty($data)) $lang = $data['iso_lang'];
		}
        return $lang;
    }

	/**
	 * Retourne la langue en cours de session sinon retourne fr par défaut
	 * @return string
	 */
	public function currentLanguage(): string {
		$lang = '';
		$user_langs = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);

		foreach($user_langs as $ul) {
			$iso = strtolower(substr(chop($ul),0,2));

			if(array_key_exists($iso,$this->langs)) {
				$lang = $iso;
				break;
			}
		}

		if(!$lang) {
			$default = $this->setDefaultLanguage();
			$default = $default ?: (http_request::isSession('strLangue') ? $_SESSION['strLangue'] : null);

			if($default) {
				$this->defaultLang = $default;
				$lang = $default;
			}
		}
		else {
			$this->defaultLang = $lang;
		}

        if(http_request::isGet('strLangue')) $lang = $_GET['strLangue'];

		return $lang;
	}

	/**
	 * @access private
	 * return void
	 * Le chemin du dossier des plugins
	 */
	private function DirPlugins(){
		return component_core_system::basePath();
	}
	/**
	 * Chargement du fichier de configuration suivant la langue en cours de session.
	 * @access private
	 * return string
	 */
	private function pathConfigLoad($configfile){
		try {
			return $configfile.$this->lang.'.conf';
		}catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
	}

	/**
	 * 
	 * Initialise la fonction configLoad de smarty
	 * @param string $section
	 */
	public function configLoad($section = ''){
	    try {
            frontend_model_smarty::getInstance($this)->configLoad($this->pathConfigLoad($this->ConfigFile), $section);
            if (file_exists(component_core_system::basePath() . '/skin/' . $this->theme . '/i18n/')) {
                frontend_model_smarty::getInstance($this)->configLoad($this->pathConfigLoad('theme_'));
            }
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
	}

	/**
	 * Charge le theme selectionné ou le theme par défaut
	 * @return string
	 */
	public function loadTheme(): string {
		$theme = 'default';
		$selectedTheme = $this->settings['theme'];
		if(!empty($selectedTheme)) {
			if($selectedTheme !== 'default') {
				if(file_exists(component_core_system::basePath().'/skin/'.$selectedTheme.'/')) {
					$theme = $selectedTheme;
				}
				else {
					$logger = new debug_logger(MP_LOG_DIR);
					$logger->log('php', 'error', 'An error has occured : template '.$selectedTheme.' is not found', debug_logger::LOG_MONTH);
				}
			}
		}
		return $theme;
	}

	/**
	 * Function load public theme
	 * @return string
	 */
	public function themeSelected(): string {
        return $this->theme;
	}

    /**
     * Chargement du type de cache
     * @param $smarty
     * @throws Exception
     * @return void
     */
    public function setCache($smarty) {
		$config = empty($this->settings) ? $this->collectionsSetting->fetchData(['context'=>'one','type'=>'setting'],['name'=>'cache']) : $this->settings['cache'];

        switch($config){
            case 'none':
                $smarty->setCaching(false);
                break;
            case 'files':
                $smarty->setCaching(true);
                $smarty->setCachingType('file');
                break;
            case 'apc':
                $smarty->setCaching(true);
                $smarty->setCachingType('apc');
                break;
        }
    }

    /**
     * Chargement des widgets additionnel du template courant
     * @param void $smarty
     * @param void $rootpath
     * @param bool $debug
     * @throws Exception
     * @return void
     */
	public function addWidgetDir($smarty,$rootpath,$debug=false){
        $add_widget_dir = $rootpath."skin/".$this->theme.'/widget/';
        if(file_exists($add_widget_dir)){
            if(is_dir($add_widget_dir)){
                $smarty->addPluginsDir($add_widget_dir);
            }
        }
        if($debug == true){
            /*$firephp = new magixcjquery_debug_magixfire();
            $firephp->magixFireDump('Widget in skin',$smarty->getPluginsDir());*/
        }
	}

    /**
     * @access public
     * Affiche le template
     * @param string|object $template
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param object $parent
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null){
    	if($this->amp) {
			$theme = $this->theme;
			if(file_exists(component_core_system::basePath().'/skin/'.$theme.'/amp/'.$template)){
				$template = 'amp/'.$template;
			}
		}
    	$this->assign('modelTemplate',$this);
    	$this->assign('breadcrumbs',$this->breadcrumb->getBreadcrumb());
        //$this->configLoad();
        frontend_model_smarty::getInstance($this)->display($template, $cache_id, $compile_id, $parent);
    }

    /**
     * @access public
     * Retourne le template
     * @param string|object $template
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param object $parent
     * @param bool   $display           true: display, false: fetch
     * @param bool   $merge_tpl_vars    if true parent template variables merged in to local scope
     * @param bool   $no_output_filter  if true do not run output filter
     * @return string rendered template output
     */
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false){
        return frontend_model_smarty::getInstance()->fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
    }

    /**
     * @access public
     * Assign les variables dans les fichiers phtml
     * @param string|array $tpl_var
     * @param string $value
     * @param bool $nocache
     * @return void
     */
	public function assign($tpl_var, $value = null, $nocache = false){
		try {
			if (is_array($tpl_var)) {
				frontend_model_smarty::getInstance()->assign($tpl_var);
			}
			else {
				if($tpl_var) {
					frontend_model_smarty::getInstance()->assign($tpl_var,$value,$nocache);
				}
				else {
					throw new Exception('Unable to assign a variable in template');
				}
			}
		} catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}
	}

	/**
	 * Test si le cache est valide
	 * @param string|object $template
	 * @param mixed $cache_id
	 * @param mixed $compile_id
	 * @param object $parent
	 */
	public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null){
		return frontend_model_smarty::getInstance()->isCached($template, $cache_id, $compile_id, $parent);
	}

    /**
     * Charge les variables du fichier de configuration dans le site
     * @param string $varname
     * @param bool $search_parents
     * @return string
     */
	public function getConfigVars($varname = null, $search_parents = true){
		return frontend_model_smarty::getInstance()->getConfigVars($varname, $search_parents);
	}

    /**
     * Returns a single or all template variables
     *
     * @param  string  $varname        variable name or null
     * @param  string  $_ptr           optional pointer to data object
     * @param  boolean $search_parents include parent templates?
     * @return string  variable value or or array of variables
     */
    public function getTemplateVars($varname = null, $_ptr = null, $search_parents = true){
        return frontend_model_smarty::getInstance()->getTemplateVars($varname, $_ptr, $search_parents);
    }

    /**
     * Get config directory
     *
     * @param mixed index of directory to get, null to get all
     * @return array|string configuration directory
     */
    public function getConfigDir($index=null){
        return frontend_model_smarty::getInstance()->getConfigDir($index);
    }

    /**
     * @return array
     */
    public function setDefaultConfigDir(){
        return array(
            component_core_system::basePath()."locali18n/",
            component_core_system::basePath() . "skin/" . $this->theme . '/i18n/'
        );
    }
	
	/**
	 * Ajoute un ou plusieurs dossier de configuration et charge les fichiers associés ainsi que les variables
	 * @access public
	 * @param array $addConfigDir
	 * @param array $load_files
	 * @param bool $debug
	 */
	public function addConfigFile(array $addConfigDir,array $load_files,$debug=false){
		try {
			if(is_array($addConfigDir)){
				$setDefaultConfigDir = $this->setDefaultConfigDir();
				//frontend_model_smarty::getInstance()->addConfigDir($addConfigDir);
				frontend_model_smarty::getInstance($this)->setConfigDir(array_merge($setDefaultConfigDir,$addConfigDir));
			}
			else{
				throw new Exception('Error: addConfigDir is not array');
			}
		}
		catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'conf', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}

		/*if(is_array($load_files)){
			foreach ($load_files as $row=>$val){
				if(is_string($row)){
					if(array_key_exists($row, $load_files)){
						frontend_model_smarty::getInstance()->configLoad(self::pathConfigLoad($row), $val);
					}
				}else{
					frontend_model_smarty::getInstance()->configLoad(self::pathConfigLoad($load_files[$row]));
				}
			}
		}*/
		try {
			if(is_array($load_files)){
				foreach ($load_files as $row=>$val){
					if(is_string($row)){
						if(array_key_exists($row, $load_files)){
							frontend_model_smarty::getInstance($this)->configLoad($row.$this->lang.'.conf',$val);
						}
					}else{
						frontend_model_smarty::getInstance($this)->configLoad($load_files[$row].$this->lang.'.conf');
					}
				}
			}
			else{
				throw new Exception('Error: load_files is not array');
			}
		}
		catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'conf', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}

		if($debug!=false){
            $config_dir = $this->getConfigDir();
            print '<pre>';
            var_dump($config_dir);
            print '</pre>';
            print '<pre>';
            print_r($load_files);
            print '</pre>';
            print '<pre>';
            print $this->getConfigVars();
            print '</pre>';
		}
	}
}