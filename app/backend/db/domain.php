<?php
class backend_db_domain
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
				case 'domain':
					$cond = '';
					if (isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if ($q !== '') {
								$cond .= $nbc ? 'AND ' : 'WHERE ';
								$p = 'p'.$nbc;
								switch ($key) {
									case 'id_domain':
										$cond .= 'd.'.$key.' = :'.$p.' ';
										break;
									case 'url_domain':
										$cond .= "d.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}
					$sql = "SELECT d.* FROM mc_domain AS d $cond";
					break;
				case 'langs':
					$sql = 'SELECT dl.*,lang.iso_lang, lang.name_lang
							FROM mc_domain_language AS dl
							JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
							WHERE id_domain = :id';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'domain':
					$sql = 'SELECT * FROM mc_domain WHERE id_domain = :id';
					break;
				case 'count':
					$sql = 'SELECT count(id_domain) AS nb FROM mc_domain';
					break;
				case 'lastLanguage':
					$sql = 'SELECT dl.*,lang.iso_lang, lang.name_lang
						FROM mc_domain_language AS dl
						JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
						WHERE dl.id_domain = :id
						ORDER BY dl.id_domain_lg DESC LIMIT 0,1';
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
			case 'newDomain':
				$sql = 'INSERT INTO mc_domain (url_domain,default_domain,canonical_domain) VALUE (:url_domain,:default_domain,:canonical_domain)';
				break;
			case 'newLanguage':
				$sql = 'INSERT INTO `mc_domain_language` (id_domain,id_lang,default_lang) VALUES (:id_domain,:id_lang,:default_lang)';
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
			case 'domain':
				$sql = 'UPDATE mc_domain 
						SET
							url_domain = :url_domain,
							tracking_domain = :tracking_domain, 
							default_domain=:default_domain,
							canonical_domain=:canonical_domain
                		WHERE id_domain = :id_domain';
				break;
			case 'modules':
				$sql = "UPDATE `mc_config`
						SET `status` = CASE `attr_name`
							WHEN 'pages' THEN :pages
							WHEN 'news' THEN :news
							WHEN 'catalog' THEN :catalog
							WHEN 'about' THEN :about
						END
						WHERE `attr_name` IN ('pages','news','catalog','about')";
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
			case 'delDomain':
				$sql = 'DELETE FROM mc_domain WHERE id_domain IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delLanguage':
				$sql = 'DELETE FROM mc_domain_language WHERE id_domain_lg IN ('.$params['id'].')';
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