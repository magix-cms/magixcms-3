<?php
class backend_db_language{
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
				case 'langs':
					$cond = '';
					if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if($q !== '') {
								$cond .= $nbc ? 'AND ' : 'WHERE ';
								$p = 'p'.$nbc;
								switch ($key) {
									case 'id_lang':
									case 'default_lang':
									case 'active_lang':
										$cond .= 'lang.'.$key.' = :'.$p.' ';
										break;
									case 'name_lang':
									case 'iso_lang':
										$cond .= "lang.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}
					$sql = "SELECT lang.* FROM mc_lang AS lang $cond";
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'lang':
					$sql = 'SELECT * FROM mc_lang WHERE id_lang = :id';
					break;
				case 'count':
					$sql = 'SELECT count(id_lang) AS nb FROM mc_lang';
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
			case 'newLang':
				$sql = 'INSERT INTO mc_lang (iso_lang,name_lang,default_lang,active_lang)
                		VALUE (:iso_lang,:name_lang,:default_lang,:active_lang)';
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
			case 'lang':
				$sql = 'UPDATE mc_lang 
						SET
							iso_lang = :iso_lang,
							name_lang = :name_lang,
							default_lang = :default_lang,
							active_lang = :active_lang
						WHERE id_lang = :id_lang';
				break;
			case 'langActive':
				$sql = 'UPDATE mc_lang SET active_lang = :active_lang WHERE id_lang IN ('.$params['id_lang'].')';
				$params = array(
					'active_lang' => $params['active_lang']
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

		switch ($config['type']) {
			case 'page':
				$sql = 'DELETE FROM `mc_lang` WHERE `id_lang` IN ('.$params['id'].')';
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