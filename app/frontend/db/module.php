<?php
class frontend_db_module
{
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

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'bonds':
					$sql = 'SELECT * FROM mc_plugins_module WHERE plugin_name = :plugin OR module_name = :module';
					break;
				case 'mod':
					$sql = 'SELECT * FROM mc_plugins_module WHERE plugin_name = :id';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'mod':
					$sql = 'SELECT * FROM mc_plugins_module WHERE module_name = :mname AND plugin_name = :pname';
					break;
				case 'register':
					$sql = 'SELECT * FROM mc_plugins WHERE name = :id';
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
			case 'mod':
				$sql = "INSERT INTO `mc_plugins_module` (plugin_name,module_name,active) VALUES (:pname,:mname,:active)";
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

		switch ($config['type'])
		{
			case 'mod':
				$sql = 'UPDATE mc_plugins_module SET active = :active WHERE module_name = :mname AND plugin_name = :pname';
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

		switch ($config['type'])
		{
			case 'mod':
				$sql = 'DELETE FROM mc_plugins_module WHERE module_name = :mname AND plugin_name = :pname';
				break;
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