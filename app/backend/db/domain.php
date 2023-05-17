<?php
class backend_db_domain {
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
					$query = "SELECT d.* FROM mc_domain AS d $cond";
					break;
				case 'langs':
					$query = 'SELECT dl.*,lang.iso_lang, lang.name_lang
							FROM mc_domain_language AS dl
							JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
							WHERE id_domain = :id';
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
				case 'domain':
					$query = 'SELECT * FROM mc_domain WHERE id_domain = :id';
					break;
				case 'count':
					$query = 'SELECT count(id_domain) AS nb FROM mc_domain';
					break;
				case 'lastLanguage':
					$query = 'SELECT dl.*,lang.iso_lang, lang.name_lang
						FROM mc_domain_language AS dl
						JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
						WHERE dl.id_domain = :id
						ORDER BY dl.id_domain_lg DESC LIMIT 0,1';
					break;
                case 'defaultDomain':
                    $query = 'SELECT d.* FROM mc_domain AS d WHERE d.default_domain = 1';
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
			case 'newDomain':
				$query = 'INSERT INTO mc_domain (url_domain,default_domain,canonical_domain) VALUE (:url_domain,:default_domain,:canonical_domain)';
				break;
			case 'newLanguage':
				$query = 'INSERT INTO `mc_domain_language` (id_domain,id_lang,default_lang) VALUES (:id_domain,:id_lang,:default_lang)';
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
			case 'domain':
				$query = 'UPDATE mc_domain 
						SET
							url_domain = :url_domain,
							tracking_domain = :tracking_domain, 
							default_domain=:default_domain,
							canonical_domain=:canonical_domain
                		WHERE id_domain = :id_domain';
				break;
			case 'modules':
				$query = "UPDATE `mc_config`
						SET `status` = CASE `attr_name`
							WHEN 'pages' THEN :pages
							WHEN 'news' THEN :news
							WHEN 'catalog' THEN :catalog
							WHEN 'about' THEN :about
						END
						WHERE `attr_name` IN ('pages','news','catalog','about')";
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
			case 'delDomain':
				$query = 'DELETE FROM mc_domain WHERE id_domain IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delLanguage':
				$query = 'DELETE FROM mc_domain_language WHERE id_domain_lg IN ('.$params['id'].')';
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