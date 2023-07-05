<?php
class backend_db_plugins {
	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []) {
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'list':
					$query = 'SELECT * FROM mc_plugins';
					break;
				case 'seo':
					$query = 'SELECT name FROM mc_plugins WHERE seo = 1';
					break;
                case 'mod':
                    $query = 'SELECT * FROM mc_plugins_module WHERE plugin_name = :plugin_name AND active = 1';
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
				case 'register':
					$query = 'SELECT * FROM mc_plugins WHERE name = :id';
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
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
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
                    if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                    $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
                }
			default:
				return false;
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'version':
				$query = 'UPDATE mc_plugins SET version = :version WHERE name = :name';
				break;
            case 'core':
                $query = 'UPDATE mc_plugins SET '.$config['column'].' = 1 WHERE name = :id';
                break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
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
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
		switch ($config['type']) {
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
                    if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                    $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
                }
			default:
				return false;
		}
    }
}