<?php
class backend_db_access {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'roles':
					$query = 'SELECT * FROM mc_admin_role_user
                    		ORDER BY id_role DESC';
					break;
				case 'access':
					$query = 'SELECT ace.*,module.class_name,module.name
							FROM mc_admin_access AS ace
							JOIN mc_module as module ON(ace.id_module = module.id_module)
							WHERE ace.id_role = :id';
					break;
				case 'module':
					$query = 'SELECT * FROM mc_module';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'role':
					$query = 'SELECT * FROM mc_admin_role_user WHERE id_role = :id';
					break;
				case 'lastRole':
					$query = 'SELECT * FROM mc_admin_role_user ORDER BY id_role DESC LIMIT 0,1';
					break;
				case 'lastAccess':
					$query = "SELECT ace.*,module.class_name,module.name
							FROM mc_admin_access as ace
							JOIN mc_module as module ON(ace.id_module = module.id_module)
							WHERE ace.id_role = :id
							ORDER BY ace.id_access DESC LIMIT 0,1";
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function insert(array $config, array $params = []) {
		switch ($config['type']) {
			case 'newRole':
				$query = 'INSERT INTO mc_admin_role_user (role_name) VALUE (:role_name)';
				break;
			case 'newAccess':
				$query = 'INSERT INTO mc_admin_access (id_role,id_module,view,append,edit,del,action)
                		VALUE (:id_role,:id_module,:view,:append,:edit,:del,:action)';
				$params = array(
					'id_role'   => $params['id_role'],
					'id_module' => $params['id_module'],
					'view'      => $params['view'],
					'append'    => $params['append'],
					'edit'      => $params['edit_access'],
					'del'       => $params['del'],
					'action'    => $params['action_access']
				);
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
    public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'role':
				$query = 'UPDATE mc_admin_role_user 
						SET role_name = :role_name
						WHERE id_role = :id_role';
				break;
			case 'access':
				$query = 'UPDATE mc_admin_access
						SET view = :view,
							append = :append,
							edit = :edit,
							del = :del,
							action= :action
						WHERE id_access = :id_access';
				$params = array(
					'id_access' => $params['id_access'],
					'view' => $params['view'],
					'append' => $params['append'],
					'edit' => $params['edit_access'],
					'del' => $params['del'],
					'action'=> $params['action_access']
				);
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
    public function delete(array $config, array $params = []) {
		if($config['context'] === 'role'){
			if($config['type'] === 'delRole') {
				$queries = array(
					array('request'=>'DELETE FROM mc_admin_access WHERE id_role IN('.$params['id'].')','params'=>array()),
					array('request'=>'DELETE FROM mc_admin_role_user WHERE id_role IN('.$params['id'].')','params'=>array()),
					array('request'=>'UPDATE mc_admin_access_rel SET id_role=1 WHERE id_role IN('.$params['id'].')','params'=>array())
				);

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
			}
			return false;
		}
		return false;
    }
}