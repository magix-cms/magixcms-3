<?php
class backend_db_files
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
					$sql = "SELECT conf.* FROM mc_config_img AS conf $cond";
					break;
				case 'size':
					$sql = 'SELECT * FROM mc_config_img WHERE module_img = :module_img AND attribute_img = :attribute_img';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'size':
					$sql = 'SELECT * FROM mc_config_img WHERE id_config_img = :id';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
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
			case 'newResize':
				$sql = 'INSERT INTO `mc_config_img`(module_img,attribute_img,width_img,height_img,type_img,resize_img) 
                		VALUE(:module_img,:attribute_img,:width_img,:height_img,:type_img,:resize_img)';
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
			case 'resize':
				$sql = 'UPDATE mc_config_img 
						SET 
							module_img = :module_img,
							attribute_img = :attribute_img, 
							width_img = :width_img,
							height_img = :height_img,
							type_img = :type_img,
							resize_img = :resize_img
						WHERE id_config_img = :id_config_img';
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

		switch ($config['type']) {
			case 'delResize':
				$sql = 'DELETE FROM `mc_config_img` WHERE `id_config_img` IN ('.$params['id'].')';
				$params = array();
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