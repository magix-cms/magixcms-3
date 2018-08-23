<?php
class backend_db_access{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
    public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'roles':
					$sql = 'SELECT * FROM mc_admin_role_user
                    		ORDER BY id_role DESC';
					break;
				case 'access':
					$sql = 'SELECT ace.*,module.class_name,module.name
							FROM mc_admin_access AS ace
							JOIN mc_module as module ON(ace.id_module = module.id_module)
							WHERE ace.id_role = :id';
					break;
				case 'module':
					$sql = 'SELECT * FROM mc_module';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'role':
					$sql = 'SELECT * FROM mc_admin_role_user
                    		WHERE id_role = :id';
					break;
				case 'lastRole':
					$sql = 'SELECT * FROM mc_admin_role_user
                    		ORDER BY id_role DESC LIMIT 0,1';
					break;
				case 'lastAccess':
					$sql = "SELECT ace.*,module.class_name,module.name
							FROM mc_admin_access as ace
							JOIN mc_module as module ON(ace.id_module = module.id_module)
							WHERE ace.id_role = :id
							ORDER BY ace.id_access DESC LIMIT 0,1";
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
    public function insert($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'newRole':
				$sql = 'INSERT INTO mc_admin_role_user (role_name) VALUE (:role_name)';
				break;
			case 'newAccess':
				$sql = 'INSERT INTO mc_admin_access (id_role,id_module,view,append,edit,del,action)
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
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
    public function update($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'role':
				$sql = 'UPDATE mc_admin_role_user 
						SET role_name = :role_name
						WHERE id_role = :id_role';
				break;
			case 'access':
				$sql = 'UPDATE mc_admin_access
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
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
    public function delete($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';
		$sql = '';

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
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}