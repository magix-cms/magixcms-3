<?php
class install_db_installer {
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
		switch ($config['type']) {
            case 'database':
                $query = "SELECT COUNT(DISTINCT `table_name`) FROM `information_schema`.`columns` WHERE `table_schema` = 'dsi_dev'";
                break;
			case 'lastEmployee':
				$query = 'SELECT em.* FROM mc_admin_employee AS em ORDER BY em.id_admin DESC LIMIT 0,1';
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
		return false;
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	public function insert(array $config, array $params = []) {
		switch ($config['type']) {
			case 'domain':
				$query = 'INSERT INTO mc_domain (url_domain,default_domain) VALUE (:domain,1)';
				break;
			case 'admin':
				$query = 'INSERT INTO mc_admin_employee (keyuniqid_admin,title_admin,lastname_admin,firstname_admin,email_admin,active_admin,passwd_admin,last_change_admin)
						VALUE (:keyuniqid_admin,:title_admin,:lastname_admin,:firstname_admin,:email_admin,:active_admin,:passwd_admin,NOW())';
				break;
			case 'adminRel':
				$query = 'INSERT INTO mc_admin_access_rel (id_admin,id_role)
						VALUE (:id_admin,:id_role)';
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
	 * @return bool
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'company':
				$query = "UPDATE `mc_about`
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
}