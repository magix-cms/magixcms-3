<?php
class install_db_installer
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
            case 'database':
                $sql = "SELECT COUNT(DISTINCT `table_name`) FROM `information_schema`.`columns` WHERE `table_schema` = 'dsi_dev'";
                break;
			case 'lastEmployee':
				$sql = 'SELECT em.* FROM mc_admin_employee AS em ORDER BY em.id_admin DESC LIMIT 0,1';
				break;
		}

		return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
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
			case 'domain':
				$sql = 'INSERT INTO mc_domain (url_domain,default_domain) VALUE (:domain,1)';
				break;
			case 'admin':
				$sql = 'INSERT INTO mc_admin_employee (keyuniqid_admin,title_admin,lastname_admin,firstname_admin,email_admin,active_admin,passwd_admin,last_change_admin)
						VALUE (:keyuniqid_admin,:title_admin,:lastname_admin,:firstname_admin,:email_admin,:active_admin,:passwd_admin,NOW())';
				break;
			case 'adminRel':
				$sql = 'INSERT INTO mc_admin_access_rel (id_admin,id_role)
						VALUE (:id_admin,:id_role)';
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
			case 'company':
				$sql = "UPDATE `mc_about`
					SET `value_info` = CASE `name_info`
						WHEN 'name' THEN :nme
						WHEN 'type' THEN :tpe
					END
					WHERE `name_info` IN ('name','type')";
				$params = array(
					'nme' => $params['name'],
					'tpe' => $params['type']
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
}