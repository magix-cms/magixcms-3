<?php
class backend_controller_module extends backend_db_module {
	public $plugins, $data;

	/**
	 * construct
	 */
	public function __construct(){
		//$this->plugins = new backend_controller_plugins();
		$this->data = new backend_model_data($this);
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
	 * Register module and set it as active
	 * @param $module
	 */
	public function register($module,$plugin,$active){
		if(!$this->getItems('mod',array('module_name' => $module,'plugin_name' => $plugin),'one',false)) {
			parent::insert(
				array('type' => 'mod'),
				array(
					'mname' => $module,
					'pname' => $plugin,
					'active' => $active
				)
			);
		} else {
			$this->u_register($module,$plugin,$active);
		}
	}

	/**
	 * Update register module
	 * @param $module
	 */
	public function u_register($module,$plugin,$active){
		parent::update(
			array('type' => 'mod'),
			array(
				'mname' => $module,
				'pname' => $plugin,
				'active' => $active
			)
		);
	}

	/**
	 * @param $plugin
	 * @param bool $del
	 */
	public function toggle_register($plugin,$del = false)
	{
		$bonds = $this->getItems('bonds',array('plugin' => $plugin, 'module' => $plugin),'all',false);
		foreach ($bonds as $bond) {
			$name = $bond['plugin_name'] === $plugin ? $bond['module_name'] : ($bond['module_name'] === $plugin ? $bond['plugin_name'] : null);
			$type = $bond['plugin_name'] === $plugin ? 'mod' : ($bond['module_name'] === $plugin ? 'plugin' : null);
			$mod = $this->getItems('register',$name,'one',false);
			$active = ($mod && !$bond['active']) ? 1 : 0;
			if($type === 'mod') $this->u_register($name,$plugin,$active);
			if($type === 'plugin') $this->u_register($plugin,$name,$active);
			if($del && !$mod) $this->unregister($plugin,$name);
		}
	}

	/**
	 * Unregister module
	 * @param $plugin
	 * @param $module
	 */
	public function unregister($plugin, $module){
		parent::delete(
			array('type' => 'mod'),
			array(
				'mname' => $module,
				'pname' => $plugin
			)
		);
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
			$modClass = 'plugins_'.$mod['module_name'].'_admin';
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
			$class =  new $module;
			if($class instanceof $module){
				return $class;
			}else{
				throw new Exception('not instantiate the class: '.$module);
			}
		}catch(Exception $e) {
			magixglobal_model_system::magixlog("Error plugins execute", $e);
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