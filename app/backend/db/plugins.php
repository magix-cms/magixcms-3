<?php
class backend_db_plugins
{
    /**
     * @param $config
     * @param bool $data
     * @return mixed
     * @throws Exception
	 * @deprecated
     */
    /*public function fetch($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'auth') {

            }
        }
    }*/

    /**
     * @param $config
     * @param bool $data
     * @return mixed|null
	 * @deprecated
     */
    /*public function fetchAll($config,$data = false){
        $sql = '';
        $params = false;
        if (is_array($config)) {
            if ($config['type'] === 'list') {
                $sql = 'SELECT * FROM mc_plugins';
                //$params = $data;
            }
        }
        return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
    }*/

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
				case 'list':
					$sql = 'SELECT * FROM mc_plugins';
					break;
				case 'seo':
					$sql = 'SELECT name FROM mc_plugins WHERE seo = 1';
					break;
                case 'mod':
                    $sql = 'SELECT * FROM mc_plugins_module WHERE plugin_name = :plugin_name AND active = 1';
                    break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
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
			case 'register':
				$className = 'plugins_'.$params['name'].'_admin';
				$queries = [
					[
						'request' => "INSERT INTO `mc_plugins` (`name`,`version`) VALUE (:name,:version)",
						'params' => [
							'name' => $params['name'],
							'version' => $params['version']
						]
					],
					[
						'request' => "INSERT INTO `mc_module` (`class_name`,`name`) VALUE (:class_name,:name)",
						'params' => [
							'class_name' => $className,
							'name' => $params['name']
						]
					],
					[
						'request'=>"INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`) SELECT 1, `mm`.`id_module`, 1, 1, 1, 1, 1 FROM `mc_module` `mm` WHERE `name` = :name;",
						'params'=> ['name' => $params['name']]
					]
				];

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
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
			case 'version':
				$sql = 'UPDATE mc_plugins SET version = :version WHERE name = :name';
				break;
            case 'core':
                $sql = 'UPDATE mc_plugins SET '.$config['column'].' = 1 WHERE name = :id';
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
			case 'unregister':
				$queries = array(
					array(
						'request'=>"DELETE FROM mc_plugins WHERE name = :id",
						'params'=>$params
					),
					array(
						'request'=>"DELETE FROM mc_module WHERE name = :id",
						'params'=>$params
					),
					[
						'request' => "DELETE FROM `mc_admin_access` WHERE `id_module` IN (SELECT `id_module` FROM `mc_module` as m WHERE m.name = :id)",
						'params' => $params
					],
					[
						'request' => "DELETE FROM `mc_config_img` WHERE `module_img` = 'plugins' AND `attribute_img` = :id",
						'params' => $params
					]
				);

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
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