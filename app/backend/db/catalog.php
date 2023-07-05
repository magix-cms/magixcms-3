<?php
class backend_db_catalog {
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
				case 'content':
					$query = 'SELECT a.*
							FROM mc_catalog_data AS a
							JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
					break;
				case 'category':
					$query = 'SELECT cat.*,c.url_cat, c.id_lang,lang.iso_lang, c.last_update
							FROM mc_catalog_cat AS cat
							JOIN mc_catalog_cat_content AS c ON ( c.id_cat = cat.id_cat )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.published_cat =1 AND c.id_lang = :id_lang';
					break;
				case 'product':
					$query = 'SELECT c.* , cat.url_cat, p.url_p, p.id_lang,lang.iso_lang, p.last_update
							FROM mc_catalog AS c
							JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
							JOIN mc_catalog_product_content AS p ON ( c.id_product = p.id_product )
							JOIN mc_lang AS lang ON ( p.id_lang = lang.id_lang )
							WHERE c.default_c =1 AND cat.published_cat =1 AND p.published_p =1 AND p.id_lang = :id_lang';
					break;
				case 'images':
					$query = 'SELECT img.name_img
							FROM mc_catalog_product_img AS img
							WHERE img.id_product = :id';
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
				case 'content':
					$query = 'SELECT * FROM `mc_catalog_data` WHERE `id_lang` = :id_lang';
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
		if ($config['type'] === 'newContent') {
            $queries = [
				[
					'request' => "SET @lang = :id_lang",
					'params' => ['id_lang' => $params['id_lang']]
				],
				[
					'request' => "INSERT INTO `mc_catalog_data` (`id_lang`,`name_info`,`value_info`) VALUES
							(@lang,'name',:nm),(@lang,'content',:content),(@lang,'seo_desc',:seo_desc),(@lang,'seo_title',:seo_title)",
					'params' => [
						'nm'        => $params['name'],
						'content'   => $params['content'],
						'seo_desc'  => $params['seo_desc'],
						'seo_title' => $params['seo_title']
					]
				]
			];

			try {
				component_routing_db::layer()->transaction($queries);
				return true;
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
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'content':
				$query = "UPDATE `mc_catalog_data`
                        SET `value_info` = CASE `name_info`
                            WHEN 'name' THEN :nm
                            WHEN 'content' THEN :content
                            WHEN 'seo_desc' THEN :seo_desc
						    WHEN 'seo_title' THEN :seo_title
                        END
                        WHERE `name_info` IN ('name','content','seo_desc','seo_title') AND id_lang = :id_lang";
				$params = array(
					'nm'        => $params['name'],
					'content'   => $params['content'],
                    'seo_title' => $params['seo_title'],
                    'seo_desc'  => $params['seo_desc'],
					'id_lang'   => $params['id_lang']
				);
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
}