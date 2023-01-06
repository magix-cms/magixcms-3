<?php
class frontend_db_home {
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
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$query = 'SELECT h.*,c.*,lang.iso_lang,lang.default_lang
							FROM mc_home_page AS h
							JOIN mc_home_page_content AS c USING(id_page)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)';
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
				case 'page':
					$query = 'SELECT
								c.title_page,
								c.content_page,
								c.seo_title_page,
								c.seo_desc_page
							FROM mc_home_page AS h
							JOIN mc_home_page_content AS c ON(h.id_page = c.id_page) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE lang.iso_lang = :iso AND c.published = 1';
					break;
				case 'root':
					$query = 'SELECT * FROM mc_home_page ORDER BY id_page DESC LIMIT 0,1';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_home_page_content` WHERE `id_page` = :id_page AND `id_lang` = :id_lang';
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
		    case 'newHome':
				$query = 'INSERT INTO `mc_home_page`(`date_register`) VALUES (NOW())';
				$params = array();
		    	break;
		    case 'newContent':
				$query = 'INSERT INTO `mc_home_page_content`(id_page,id_lang,title_page,content_page,seo_title_page,seo_desc_page,published) 
				  		VALUES (:id_page,:id_lang,:title_page,:content_page,:seo_title_page,:seo_desc_page,:published)';
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
				$query = 'UPDATE mc_home_page_content 
						SET 
							title_page = :title_page,
							content_page = :content_page,
							seo_title_page = :seo_title_page,
							seo_desc_page = :seo_desc_page,
							published = :published
						WHERE id_page = :id_page AND id_lang = :id_lang';
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
}