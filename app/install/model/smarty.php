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
 * @category   config 
 * @package    backend
 * @copyright  MAGIX CMS Copyright (c) 2013 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * Update : 18/03/2013
 * Configuration / extends smarty with class
 * @author Gérits Aurélien <aurelien@magix-cms.com> <aurelien@magix-dev.be>
 * @name smarty
 *
 */
/**
 * Extend class smarty
 *
 */
class install_model_smarty extends Smarty{
	/**
    * Variable statique permettant de porter l'instance unique
    */
    static protected $instance;

	protected $install_dir = 'install';
	/**
	 * function construct class
	 *
	 */
	public function __construct(){
		/**
		 * include parent var smarty
		 */
		parent::__construct(); 
		self::setParams();
		/*
		 You can remove this comment, if you prefer this JSP tag style
		 instead of the default { and }
		 $this->left_delimiter =  '<%';
		 $this->right_delimiter = '%>';
		 */
	}
	private function setPath(){
		return component_core_system::basePath();
	}
	/**
	 * Les paramètres pour la configuration de smarty 3
	 */
	protected function setParams(){
        /**
         * Path -> configs
         */
        $this->setConfigDir(array(
            self::setPath().DIRECTORY_SEPARATOR.$this->install_dir.'/i18n/'
        ));
        /**
         * Path -> templates
         */
        $this->setTemplateDir(array(
            self::setPath().DIRECTORY_SEPARATOR.$this->install_dir.'/template/'
        ));
        /**
         * path plugins
         * @var void
         */
        $this->setPluginsDir(array(
            self::setPath().'lib/smarty4/plugins/'
			,self::setPath().'app/wdcore/'
			,self::setPath().DIRECTORY_SEPARATOR.$this->install_dir.'/template/widget/'
        ));
        /**
         * Path -> compile
         */
        $this->setCompileDir(
            self::setPath().DIRECTORY_SEPARATOR.$this->install_dir.'/caching/templates_c/'
        );
		/**
		 * debugging (true/false)
		 */
		$this->debugging = false;
		/**
		 * compile (true/false)
		 */
		$this->compile_check = true;
		/**
		 * Force compile
		 * @var void
		 * (true/false)
		 */
		$this->force_compile = false;
		/**
		 * caching (true/false)
		 */
        $this->setCaching(false);
        //$this->setCachingType('apc');
        /**
         * Use sub dirs (true/false)
         */
        $this->use_sub_dirs = false;
        /**
         * cache_dir -> cache
         */
        $this->setCacheDir(self::setPath().DIRECTORY_SEPARATOR.$this->install_dir.'/caching/tpl_caches/');
		/**
		 * load pre filter
		 */
		//$this->load_filter('pre','magixmin');
		//$this->autoload_filters = array('pre' => array('magixmin'));
		$this->loadFilter('output', 'trimwhitespace');
		///////
        $this->loadPlugin('smarty_compiler_switch');
		/**
		 * 
		 * @var error_reporting
		 */
		$this->error_reporting = error_reporting() &~E_NOTICE;
		/**
		 * Security
		 */
		//$this->enableSecurity();
		/**
		 * security settings
		 */
		//$this->enableSecurity('Security_Policy');
	}
	public static function getInstance(){
        if (!isset(self::$instance))
      {
         self::$instance = new install_model_smarty();
      }
    	return self::$instance;
    }
}