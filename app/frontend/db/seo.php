<?php
class frontend_db_seo {
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
			    case 'replace':
					$query = 'SELECT * 
						FROM mc_seo 
						JOIN mc_seo_content USING(id_seo)
						LEFT JOIN mc_lang USING(id_lang)
						WHERE iso_lang = :iso
						AND level_seo = :lvl
						AND attribute_seo = :attribute
						AND type_seo = :type
						ORDER BY id_seo 
						DESC LIMIT 0,1';
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
}