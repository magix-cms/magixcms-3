<?php
class frontend_db_logo {
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
				case 'page':
					$query = 'SELECT p.img_logo,p.active_logo,c.alt_logo,c.title_logo,lang.iso_lang,lang.id_lang
						FROM mc_logo AS p
						JOIN mc_logo_content AS c USING(id_logo)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE lang.iso_lang = :iso LIMIT 0,1';
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