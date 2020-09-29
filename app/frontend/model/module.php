<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2019 magix-cms.com <support@magix-cms.com>
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
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
class frontend_model_module extends frontend_db_module {
    protected $template, $data;

	/**
	 * frontend_model_plugins constructor.
	 */
    public function __construct($t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * Get active modules and return a array of all active module instance
	 *
	 * the `extend_module` method should be executed before to ensure that
	 * all compatible modules will be loaded
	 *
	 * @return array
	 */
	public function load_module($name){
		$mods = $this->getItems('mod',$name,'all',false);
		$active_mods = array();

		foreach ( $mods as $mod ) {
			$modClass = 'plugins_'.$mod['module_name'].'_public';
			$active_mods[$mod['module_name']] = $this->get_call_class($modClass);
		}

		return $active_mods;
	}

	/**
	 * Instantiate module class
	 * @param $module
	 * @return mixed
	 */
	private function get_call_class($module){
		try{
			$class =  new $module($this->template);
			if($class instanceof $module){
				return $class;
			}else{
				throw new Exception('not instantiate the class: '.$module);
			}
		}catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'Error plugins execute : ' . $e->getMessage(), debug_logger::LOG_MONTH);
		}
	}

	/**
	 * Call module method and return result
	 * @param $mod
	 * @param $methodName
	 * @param $param_arr
	 * @return mixed
	 */
	public function call_method($mod,$methodName,$param_arr){
		if(method_exists($mod,$methodName)){
			return call_user_func_array(
				array(
					$mod,
					$methodName
				),
				$param_arr
			);
		}
	}
}