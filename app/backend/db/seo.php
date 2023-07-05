<?php
class backend_db_seo {
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
				case 'seo':
					$query = "SELECT s.*, c.content_seo 
						FROM mc_seo AS s
						JOIN mc_seo_content AS c USING ( id_seo )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE c.id_lang = :default_lang
						GROUP BY s.id_seo";
					break;
				case 'editSeo':
					$query = "SELECT s.*, c.content_seo, c.id_lang 
						FROM mc_seo AS s
						JOIN mc_seo_content AS c USING ( id_seo )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE s.id_seo = :edit";
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
				case 'root':
					$query = 'SELECT * FROM mc_seo ORDER BY id_seo DESC LIMIT 0,1';
					break;
				case 'seo':
					$query = 'SELECT * FROM mc_seo WHERE `id_seo` = :id_seo';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_seo_content` WHERE `id_seo` = :id_seo AND `id_lang` = :id_lang';
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
			case 'newSeo':
				$query = 'INSERT INTO `mc_seo`(level_seo,attribute_seo,type_seo) VALUES (:level_seo,:attribute_seo,:type_seo)';
				break;
			case 'newContent':
				$query = 'INSERT INTO `mc_seo_content`(id_seo,id_lang,content_seo) VALUES (:id_seo,:id_lang,:content_seo)';
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
			case 'content':
                $query = 'UPDATE mc_seo_content SET content_seo = :content_seo
                WHERE id_seo = :id_seo AND id_lang = :id_lang';
            	break;
            case 'data':
                $query = 'UPDATE mc_seo 
						SET 
							level_seo = :level_seo,
							attribute_seo = :attribute_seo,
							type_seo = :type_seo
                		WHERE id_seo = :id_seo';
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
			case 'page':
				$query = 'DELETE FROM `mc_seo` WHERE `id_seo` IN ('.$params['id'].')';
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
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
    }
}