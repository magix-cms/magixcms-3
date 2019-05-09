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
class install_model_template {
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
	public $lang;
	/**
	 * 
	 * Constructor
	 */
    public function __construct(){
        self::$collectionsSetting = new component_collections_setting();
		self::getReleaseData();
		$this->lang = $this->currentLanguage();
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
	 * Retourne la langue en cours de session sinon retourne fr par défaut
	 * @return string
	 * @access public 
	 * @static
	 */
	public function currentLanguage(){
		$lang = null;
		$user_langs = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$langs_available = ['fr','en'];

		foreach($user_langs as $ul) {
			$iso = strtolower(substr(chop($ul),0,2));

			if(array_key_exists($iso,$langs_available)) {
				$lang = $iso;
				break;
			}
		}

		if(!$lang) $lang = http_request::isSession('strLangue') ? form_inputFilter::isAlphaNumericMax($_SESSION['strLangue'],3) : null;

		if(http_request::isGet('strLangue')) {
			$lang = form_inputFilter::isAlphaNumericMax($_GET['strLangue'],3);
		}

		if(!$lang) $lang = 'fr';

		return $lang;
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
     * Example :
     * $this->template->configLoad();
     * $this->template->getConfigVars('my_var');
	 * 
	 * Initialise la fonction configLoad de smarty
	 * @param string $section
	 */
	public function configLoad($section = ''){
		install_model_smarty::getInstance()->configLoad(self::pathConfigLoad(self::$ConfigFile), $section);
	}

    /**
     * @param $template_dir
     */
    public function addTemplateDir($template_dir){
        install_model_smarty::getInstance()->addTemplateDir($template_dir);
    }

    /**
     * @access public
     * Affiche le template
     * @param string|object $template
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param object $parent
     * @throws SmartyException
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null){
        if(!self::isCached($template, $cache_id, $compile_id, $parent)){
            install_model_smarty::getInstance()->display($template, $cache_id, $compile_id, $parent);
        }else{
            install_model_smarty::getInstance()->display($template, $cache_id, $compile_id, $parent);
        }
    }

    /**
     * @access public
     * Retourne le template
     * @param string|object $template
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param object $parent
     * @param bool $display true: display, false: fetch
     * @param bool $merge_tpl_vars if true parent template variables merged in to local scope
     * @param bool $no_output_filter if true do not run output filter
     * @return string rendered template output
     * @throws SmartyException
     */
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false){
        if(!self::isCached($template, $cache_id, $compile_id, $parent)){
            return install_model_smarty::getInstance()->fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
        }else{
            return install_model_smarty::getInstance()->fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
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
				install_model_smarty::getInstance()->assign($tpl_var);
			}
			else {
				if($tpl_var) {
					install_model_smarty::getInstance()->assign($tpl_var,$value,$nocache);
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
     * @throws SmartyException
     */
	public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null){
		install_model_smarty::getInstance()->isCached($template, $cache_id, $compile_id, $parent);
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
		return install_model_smarty::getInstance()->getConfigVars($varname, $search_parents);
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
        return install_model_smarty::getInstance()->getTemplateVars($varname, $_ptr, $search_parents);
    }

    /**
     * Get config directory
     *
     * @param mixed index of directory to get, null to get all
     * @return array|string configuration directory
     */
    public function getConfigDir($index=null){
        return install_model_smarty::getInstance()->getConfigDir($index);
    }

	/**
	 * Ajoute un ou plusieurs dossier de configuration et charge les fichiers associés ainsi que les variables
	 * @access public
	 * @param array $addConfigDir
	 * @param array $load_files
	 * @param bool $debug
	 * @throws Exception
	 */
	public function addConfigFile(array $addConfigDir,array $load_files,$debug=false){
		if(is_array($addConfigDir)){
			install_model_smarty::getInstance()->addConfigDir($addConfigDir);
		}else{
			throw new Exception('Error: addConfigDir is not array');
		}
		if(is_array($load_files)){
			foreach ($load_files as $row=>$val){
				if(is_string($row)){
					if(array_key_exists($row, $load_files)){
                        //$this->configLoad($this->pathConfigLoad($row), $val);
                        install_model_smarty::getInstance()->configLoad($row.self::currentLanguage().'.conf',$val);
					}
				}else{
                    //$this->configLoad($this->pathConfigLoad($load_files[$row]));
                    install_model_smarty::getInstance()->configLoad($load_files[$row].self::currentLanguage().'.conf');
				}
			}
		}else{
			throw new Exception('Error: load_files is not array');
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