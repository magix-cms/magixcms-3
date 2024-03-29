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
 * @name template
 *
 */
class backend_model_template{
	/**
	 * Constante pour le chemin vers le dossier de configuration des langues statiques pour le contenu
	 * @var string
	 */
	private static $ConfigFile = 'local_';
    /**
     * @var component_collections_setting
     */
    protected static $collectionsSetting,$collectionsLang;
	/**
	 * singleton dbconfig
	 * @access public
	 * @var void
	 */
	static protected $frontendtheme;
	public $lang, $settings;
	/**
	 * 
	 * Constructor
	 */
    public function __construct(){
        self::$collectionsSetting = new component_collections_setting();
        //self::$collectionsLang = new component_collections_language();
		$this->init();
    }

    private function init() {
		self::getReleaseData();
		$this->lang = $this->currentLanguage();
		$this->settings = self::$collectionsSetting->getSetting();
	}

	/**
	 * 
	 */
	public static function frontendTheme(){
        if (!isset(self::$frontendtheme)){
         	self::$frontendtheme = new backend_model_template();
        }
    	return self::$frontendtheme;
    }


	/**
	 *
	 */
	public function getReleaseData()
	{
		$basePath = component_core_system::basePath().DIRECTORY_SEPARATOR;
		$XMLFiles = $basePath . 'release.xml';
		if (file_exists($XMLFiles)) {
			try {
				if ($stream = fopen($XMLFiles, 'r')) {
					$streamData = stream_get_contents($stream, -1, 0);
					$streamData = urldecode($streamData);
					$xml = simplexml_load_string($streamData, null, LIBXML_NOCDATA);
					$newData = array();
					foreach ($xml->children() as $item => $value) {
						$newData[$item] = $value->__toString();
					}
					fclose($stream);
					self::assign('releaseData',$newData);
				}
			} catch (Exception $e) {
				$logger = new debug_logger(MP_LOG_DIR);
				$logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
			}
		}
	}

    /**
     * @access public static
     * Paramètre de langue get
     */
	public static function getLanguage(){
        if(http_request::isGet('strLanguage')){
            return form_inputFilter::isAlphaNumericMax($_GET['strLanguage'],3);
        }
	}

	/**
	 * Retourne la langue en cours de session sinon retourne fr par défaut
	 * @return string
	 * @access public 
	 * @static
	 */
	public static function currentLanguage(){
        if(http_request::isGet('strLanguage')){
            $lang = self::getLanguage();
        }else{
            /*if(self::$collectionsLang instanceof component_collections_language){
                if(http_request::isSession('strLanguage')){
                    $lang = form_inputFilter::isAlphaNumericMax($_SESSION['strLanguage'],3);
                }else{
                    $data = self::$collectionsLang->setDefault();
                    if($data != null){
                        $lang = $data['iso'];
                    }
                }
            }*/
			if(http_request::isSession('strLanguage')){
				$lang = form_inputFilter::isAlphaNumericMax($_SESSION['strLanguage'],3);
			} else {
				$lang = 'fr';
			}
        }

		return $lang;
	}
	/**
	 * @access private
	 * return void
	 * Le chemin du dossier des plugins
	 */
	public function pluginsBasePath(){
		return component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR;
	}
	/**
	 * Chargement du fichier de configuration suivant la langue en cours de session.
	 * @access private
	 * return string
	 */
	private function pathConfigLoad($configfile){
		try {
			return $configfile.self::currentLanguage().'.conf';
		}catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
	}

	/**
     * Example :
     * $this->template->configLoad();
     * $this->template->getConfigVars('my_var');
	 * 
	 * Initialise la fonction configLoad de smarty
	 * @param string $section
	 */
	public function configLoad($section = ''){
		backend_model_smarty::getInstance()->configLoad(self::pathConfigLoad(self::$ConfigFile), $section);
	}

	/**
	 * Charge le theme selectionné ou le theme par défaut
	 */
	public function load_theme(){
		//$db = self::$collectionsSetting->fetch('theme');
		/*if($db['setting_value'] != null){
			if($db['setting_value'] == 'default'){
				$theme =  $db['setting_value'];
			}elseif(file_exists(component_core_system::basePath().'/skin/'.$db['setting_value'].'/')){
				$theme =  $db['setting_value'];
			}else{
				try {
					$theme = 'default';
	        		throw new Exception('template '.$db['setting_value'].' is not found');
				}catch(Exception $e) {
                    $logger = new debug_logger(MP_LOG_DIR);
                    $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
                }
			}
		}else{
			$theme = 'default';
		}*/
		if(file_exists(component_core_system::basePath().'/skin/')){
			return;
		}
		//return $theme;
	}

	/**
	 * Function load public theme
	 * @see backend_config_theme
	 */
	public static function themeSelected(){
		if (!self::frontendTheme() instanceof backend_model_template){
			throw new Exception('template load is not found');
		}
		return self::frontendTheme()->load_theme();
	}

    /**
     * Chargement du type de cache
     * @param $smarty
     * @throws Exception
     * @return void
     */
    public function setCache($smarty){
        if (!self::frontendTheme() instanceof backend_model_template){
            throw new Exception('template instance is not found');
        }else{
            $config = self::$collectionsSetting->fetchData(['context' => 'one','type' => 'setting'],['name' => 'cache']);
            switch($config['setting_value']){
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
		if (!self::frontendTheme() instanceof backend_model_template){
			throw new Exception('template instance is not found');
		}else{
			$add_widget_dir = $rootpath."skin/widget/";
			if(file_exists($add_widget_dir)){
				if(is_dir($add_widget_dir)){
					$smarty->addPluginsDir($add_widget_dir);
				}
			}
			/*if($debug == true){
				$firephp = new magixcjquery_debug_magixfire();
				$firephp->magixFireDump('Widget in skin',$smarty->getPluginsDir());
			}*/
		}
	}

    /**
     * @param $template_dir
     */
    public function addTemplateDir($template_dir){
        backend_model_smarty::getInstance()->addTemplateDir($template_dir);
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
        backend_model_smarty::getInstance()->display($template, $cache_id, $compile_id, $parent);
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
        if(!self::isCached($template, $cache_id, $compile_id, $parent)){
            return backend_model_smarty::getInstance()->fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
        }else{
            return backend_model_smarty::getInstance()->fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
        }
    }

    /**
     * @access public
     * Assign les variables dans les fichiers phtml
     * @param array|string $tpl_var
     * @param string $value
     * @param bool $nocache
     * @return void
     */
	public function assign($tpl_var, $value = null, $nocache = false){
		try {
			if (is_array($tpl_var)) {
				backend_model_smarty::getInstance()->assign($tpl_var);
			}
			else {
				if($tpl_var) {
					backend_model_smarty::getInstance()->assign($tpl_var,$value,$nocache);
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
		backend_model_smarty::getInstance()->isCached($template, $cache_id, $compile_id, $parent);
	}

    /**
     * Charge les variables du fichier de configuration dans le script
     * $this->template->configLoad();
     * $this->template->getConfigVars('my_var');
     * @param string $varname
     * @param bool $search_parents
     * @return string
     */
	public function getConfigVars($varname = null, $search_parents = true){
		return backend_model_smarty::getInstance()->getConfigVars($varname, $search_parents);
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
        return backend_model_smarty::getInstance()->getTemplateVars($varname, $_ptr, $search_parents);
    }

    /**
     * Get config directory
     *
     * @param mixed index of directory to get, null to get all
     * @return array|string configuration directory
     */
    public function getConfigDir($index=null){
        return backend_model_smarty::getInstance()->getConfigDir($index);
    }

	/**
	 * Ajoute un ou plusieurs dossier de configuration et charge les fichiers associés ainsi que les variables
	 * @access public
	 * @param array $addConfigDir
	 * @param array $load_files
	 * @param bool $debug
	 */
	public function addConfigFile(array $addConfigDir,array $load_files,bool $debug=false){
		backend_model_smarty::getInstance()->addConfigDir($addConfigDir);
		foreach ($load_files as $row=>$val){
			if(is_string($row)){
				if(array_key_exists($row, $load_files)){
					//$this->configLoad($this->pathConfigLoad($row), $val);
					backend_model_smarty::getInstance()->configLoad($row.self::currentLanguage().'.conf',$val);
				}
			}else{
				//$this->configLoad($this->pathConfigLoad($load_files[$row]));
				backend_model_smarty::getInstance()->configLoad($load_files[$row].self::currentLanguage().'.conf');
			}
		}

		if($debug){
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