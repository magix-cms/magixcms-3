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
    protected $template, $data, $pluginsCollection, $mods, $installedPlugins;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
        $this->pluginsCollection = new component_collections_plugins();
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
	 * Get active modules and return a array of all active module
	 * @return array
	 */
	public function get_active_module($name){
		$mods = $this->getItems('mod',$name,'all',false);
		$active_mods = [];

		foreach ( $mods as $mod ) {
			$active_mods[$mod['module_name']] = true;
		}

		return $active_mods;
	}

	/**
	 * Get active modules and return a array of all active module instance
	 *
	 * the `extend_module` method should be executed before to ensure that
	 * all compatible modules will be loaded
	 *
	 * @param string $plugin_name
	 * @param string|null $module_name
	 * @return array|object
	 */
	public function load_module(string $plugin_name,string $module_name = null){
		$mods = $this->getItems('mod',$plugin_name,'all',false);
		if($module_name === null) {
			$active_mods = [];
			foreach ( $mods as $mod ) {
				$modClass = 'plugins_'.$mod['module_name'].'_public';
				$active_mods[$mod['module_name']] = $this->get_call_class($modClass);
			}
			return $active_mods;
		}
		else {
			foreach ( $mods as $mod ) {
				if($mod['module_name'] === $module_name) {
					$modClass = 'plugins_'.$mod['module_name'].'_public';
					return $this->get_call_class($modClass);
				}
			}
		}
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

    /**
     * @deprecated
     * @param $type
     * @param $method
     * @return array|mixed|void
     */
    public function getOverride($type,$method,$param_arr= array()){
        $newMethod = array();
        $collection = $this->pluginsCollection->fetchAll();
        $module = ['home','about','pages','news','catalog','category','product'];
        if(in_array($type,$module)) {
            //print_r($collection);
            $newarr = array();
            foreach ($collection as $key => $item) {
                if ($item[$type] == 1) {
                    $newarr[] = array(
                        'name' => $item['name']
                    );
                }
            }

            foreach ($newarr as $item) {
                $module_class = 'plugins_' . $item['name'] . '_public';
                if (class_exists($module_class)) {
                    $newMethod = $this->call_method(
                        $this->get_call_class($module_class)
                        , $method,
                        $param_arr);
                    //function_exists()
                    //print $module_class. ' : '.$method;
                    /*if(method_exists($module_class,$method)){
                        return call_user_func_array(
                            array(
                                $this->get_call_class($module_class),
                                $method
                            ),
                            $param_arr
                        );
                    }*/
                    return $newMethod;
                }
            }
        }
    }

    /**
     * @param $type
     * @return array|void
     */
    private function loadExtendCore($type){
        $newMethod = array();
        $collection = $this->pluginsCollection->fetchAll();
        $module = ['home','about','pages','news','catalog','category','product'];
        $active_mods = array();
        if(in_array($type,$module)) {
            //print_r($collection);
            foreach ($collection as $key => $item) {
                if ($item[$type] == 1) {
                    //$mods[] = $item['name'];
                    $modClass = 'plugins_' . $item['name'] . '_public';

                    if(class_exists($modClass)) $active_mods[$item['name']] = $this->get_call_class($modClass);
                }
            }
            return $active_mods;
        }
    }

    /**
     * @param $type
     * @param $method
     * @param array $params
     * @return array
     */
    public function extendDataArray($type,$method,array $params = []) : array{
        /*if(!isset($this->mods)) $this->mods = $this->loadExtendCore($type);
        $loadMethod = [];
        if(!empty($this->mods)) {
            foreach ($this->mods as $mod){
                //print $mod;
                if(method_exists($mod,$method)){
                    $loadMethod[] = call_user_func_array(
                        array(
                            $mod,
                            $method
                        ),
                        [$params]
                    );
                }
            }
        }
        return $loadMethod;*/
        $this->mods = $this->loadExtendCore($type);
        $loadMethod = [];
        if(!empty($this->mods)) {
            foreach ($this->mods as $mod){
                //print $mod;
                if(method_exists($mod,$method)){
                    $loadMethod[] = call_user_func_array(
                        [$mod, $method],
                        [$params]
                    );
                }
            }
        }
        return $loadMethod;
    }
}