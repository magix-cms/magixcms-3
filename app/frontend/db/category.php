<?php
class frontend_db_category {
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
                case 'pages':
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                    $query = "SELECT p.*,
								   c.name_cat,
								   c.url_cat,
								   c.resume_cat,
								   c.content_cat,
								   c.published_cat,
       								COALESCE(c.alt_img, c.name_cat) as alt_img,
									COALESCE(c.title_img, c.alt_img, c.name_cat) as title_img,
									COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_cat) as caption_img,
								   c.seo_title_cat,
								   c.seo_desc_cat,
								   lang.id_lang,
								   lang.iso_lang,
								   lang.default_lang
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c ON(p.id_cat = c.id_cat) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
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
					$query = 'SELECT * FROM mc_catalog_cat ORDER BY id_cat DESC LIMIT 0,1';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_catalog_cat_content` WHERE `id_cat` = :id_cat AND `id_lang` = :id_lang';
					break;
				case 'wsEdit':
					$query = 'SELECT * FROM mc_catalog_cat WHERE `id_cat` = :id';
					break;
				case 'image':
					$query = 'SELECT img_cat FROM mc_catalog_cat WHERE `id_cat` = :id_cat';
					break;
				case 'page':
					$query = 'SELECT * FROM mc_catalog_cat WHERE `id_cat` = :id_cat';
					break;
                case 'pageLang':
                    $query = 'SELECT p.*,c.*,lang.*
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c USING(id_cat)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_cat = :id
							AND lang.iso_lang = :iso';
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
			case 'page':
				$cond = $params['id_parent'] != NULL ? 'IN ('.$params['id_parent'].')' : 'IS NULL' ;
				$query = "INSERT INTO `mc_catalog_cat`(id_parent,order_cat,date_register) 
						SELECT :id_parent,COUNT(id_cat),NOW() FROM mc_catalog_cat WHERE id_parent $cond";
				break;
			case 'content':
				$query = 'INSERT INTO `mc_catalog_cat_content`(id_cat,id_lang,name_cat,url_cat,resume_cat,content_cat,seo_title_cat,seo_desc_cat,published_cat) 
			  			VALUES (:id_cat,:id_lang,:name_cat,:url_cat,:resume_cat,:content_cat,:seo_title_cat,:seo_desc_cat,:published_cat)';
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
			case 'page':
				$query = 'UPDATE mc_catalog_cat 
						SET 
							id_parent = :id_parent,
							menu_cat = :menu_cat
						WHERE id_cat = :id_cat';
				break;
			case 'content':
				$query = 'UPDATE mc_catalog_cat_content 
						SET 
							name_cat = :name_cat, 
							url_cat = :url_cat, 
							resume_cat = :resume_cat, 
							content_cat = :content_cat,
							seo_title_cat=:seo_title_cat, 
                            seo_desc_cat=:seo_desc_cat, 
                            published_cat=:published_cat
						WHERE id_cat = :id_cat AND id_lang = :id_lang';
				break;
			case 'img':
				$query = 'UPDATE mc_catalog_cat SET img_cat = :img_cat WHERE id_cat = :id_cat';
				break;
			case 'order':
				$query = 'UPDATE mc_catalog_cat SET order_cat = :order_cat WHERE id_cat = :id_cat';
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
			case 'delPages':
				$query = 'DELETE FROM `mc_catalog_cat` WHERE `id_cat` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delProduct':
				$query = 'DELETE FROM `mc_catalog` WHERE `id_catalog` IN ('.$params['id'].')';
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