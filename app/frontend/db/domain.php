<?php
class frontend_db_domain {
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
					$query = "SELECT d.* FROM mc_domain AS d";
					break;
				case 'languages':
					$query = 'SELECT dl.id_lang,lang.iso_lang, lang.name_lang
						FROM mc_domain_language AS dl
						JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
						WHERE dl.id_domain = :id
						ORDER BY dl.default_lang DESC,dl.id_lang';
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
				case 'currentDomain':
					$query = 'SELECT d.* FROM mc_domain AS d WHERE d.url_domain = :url';
					break;
				case 'defaultDomain':
					$query = 'SELECT d.* FROM mc_domain AS d WHERE d.default_domain = 1';
					break;
				case 'language':
					$query = 'SELECT dl.id_lang,lang.iso_lang, lang.name_lang
							FROM mc_domain_language AS dl
							JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
							WHERE dl.id_domain = :id AND dl.default_lang = 1';
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