<?php
class backend_db_files {
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
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'sizes':
					$cond = '';
					if (isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						$params = array();
						foreach ($config['search'] as $key => $q) {
							if ($q !== '') {
								$cond .= $nbc ? 'AND ' : 'WHERE ';
								$p = 'p'.$nbc;
								switch ($key) {
									case 'id_config_img':
										$cond .= 'conf.'.$key.' = :'.$p.' ';
										break;
									case 'module_img':
									case 'attribute_img':
										$cond .= "conf.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}
					$query = "SELECT conf.* FROM mc_config_img AS conf $cond";
					break;
				case 'size':
					$query = 'SELECT * FROM mc_config_img WHERE module_img = :module_img AND attribute_img = :attribute_img';
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
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'size':
					$query = 'SELECT * FROM mc_config_img WHERE id_config_img = :id';
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
			case 'newResize':
				$query = 'INSERT INTO `mc_config_img`(module_img,attribute_img,width_img,height_img,type_img,resize_img) 
                		VALUE(:module_img,:attribute_img,:width_img,:height_img,:type_img,:resize_img)';
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
			case 'resize':
				$query = 'UPDATE mc_config_img 
						SET 
							module_img = :module_img,
							attribute_img = :attribute_img, 
							width_img = :width_img,
							height_img = :height_img,
							type_img = :type_img,
							resize_img = :resize_img
						WHERE id_config_img = :id_config_img';
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
		switch ($config['type']) {
			case 'delResize':
				$query = 'DELETE FROM `mc_config_img` WHERE `id_config_img` IN ('.$params['id'].')';
				$params = array();
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}