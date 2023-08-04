<?php
class backend_db_category {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []){
		$dateFormat = new component_format_date();

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$limit = '';
					if($config['offset']) {
						$limit = ' LIMIT 0, '.$config['offset'];
						if(isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
						}
					}

					$query = "SELECT p.id_cat, c.name_cat, c.content_cat, c.link_label_cat, c.link_title_cat, c.seo_title_cat, c.seo_desc_cat, p.menu_cat, p.date_register, p.img_cat
							FROM mc_catalog_cat AS p
								JOIN mc_catalog_cat_content AS c USING ( id_cat )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								WHERE c.id_lang = :default_lang AND p.id_parent IS NULL  
							ORDER BY p.order_cat".$limit;

					if(isset($config['search'])) {
						$cond = '';
						if(is_array($config['search']) && !empty($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if($q !== '') {
									$cond .= 'AND ';
									$p = 'p'.$nbc;
									switch ($key) {
										case 'id_cat':
										case 'menu_cat':
											$cond .= 'p.'.$key.' = :'.$p.' ';
											break;
										case 'published_cat':
											$cond .= 'c.'.$key.' = :'.$p.' ';
											break;
										case 'name_cat':
											$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'parent_cat':
											$cond .= "ca.name_cat"." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'date_register':
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
									}
									$params[$p] = $q;
									$nbc++;
								}
							}

							$query = "SELECT p.id_cat, c.name_cat, c.content_cat, c.seo_title_cat, c.seo_desc_cat, p.menu_cat, p.date_register, p.img_cat, ca.name_cat AS parent_cat
									FROM mc_catalog_cat AS p
										JOIN mc_catalog_cat_content AS c USING ( id_cat )
										JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
										LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
										LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
										WHERE c.id_lang = :default_lang $cond
									ORDER BY p.order_cat".$limit;
						}
					}
					break;
				case 'pagesChild':
					$cond = '';
					if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if($q !== '') {
								$cond .= 'AND ';
								$p = 'p'.$nbc;
								switch ($key) {
									case 'id_cat':
									case 'menu_cat':
										$cond .= 'p.'.$key.' = :'.$p.' ';
										break;
									case 'name_cat':
										$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
									case 'date_register':
										$q = $dateFormat->date_to_db_format($q);
										$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}
					$query = "SELECT p.id_cat, c.name_cat, c.resume_cat, c.content_cat, c.link_label_cat, c.link_title_cat, c.seo_title_cat, c.seo_desc_cat, p.menu_cat, p.date_register, p.img_cat
						FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c USING ( id_cat )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
							LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat and ca.id_lang = c.id_lang ) 
							WHERE p.id_parent = :id AND c.id_lang = :default_lang $cond";
					break;
				case 'pagesSelect':
					$query = "SELECT p.id_parent,p.id_cat, c.name_cat , ca.name_cat AS parent_cat
						FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c USING ( id_cat )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
							LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
							WHERE c.id_lang = :default_lang 
						ORDER BY p.id_cat DESC";
					break;
				case 'pagesPublishedSelect':
					$query = "SELECT p.id_parent,p.id_cat, c.name_cat , ca.name_cat AS parent_cat
							FROM mc_catalog_cat AS p
								JOIN mc_catalog_cat_content AS c USING ( id_cat )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
								LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
								WHERE c.id_lang = :default_lang
								AND c.published_cat = 1
							ORDER BY p.id_cat DESC";
					break;
				case 'page':
					$query = 'SELECT p.*,c.*,lang.*
						FROM mc_catalog_cat AS p
						JOIN mc_catalog_cat_content AS c USING(id_cat)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE p.id_cat = :edit';
					break;
				case 'img':
					$query = 'SELECT p.id_cat, p.img_cat
						FROM mc_catalog_cat AS p WHERE p.img_cat IS NOT NULL';
					break;
				case 'catRoot':
					$query = 'SELECT DISTINCT c.id_cat, c.id_parent, cont.name_cat, cc.id_parent AS parent_id
						FROM mc_catalog_cat AS c
						LEFT JOIN mc_catalog_cat AS cc ON ( cc.id_parent = c.id_cat )
						LEFT JOIN mc_catalog_cat_content AS cont ON ( c.id_cat = cont.id_cat )
						LEFT JOIN mc_lang AS lang ON ( cont.id_lang = lang.id_lang )
						WHERE cont.id_lang = :default_lang AND c.id_parent IS NULL';
					break;
				case 'cats':
					$query = 'SELECT DISTINCT c.id_cat, c.id_parent, cont.name_cat
						FROM mc_catalog_cat AS c
						LEFT JOIN mc_catalog_cat_content AS cont ON ( c.id_cat = cont.id_cat )
						LEFT JOIN mc_lang AS lang ON ( cont.id_lang = lang.id_lang )
						WHERE cont.id_lang = :default_lang';
					break;
				case 'subcat':
					$query = 'SELECT c.id_parent,c.id_cat, cont.name_cat, cc.id_parent AS parent_id
						FROM mc_catalog_cat AS c
						LEFT JOIN mc_catalog_cat AS cc ON ( cc.id_parent = c.id_cat )
						LEFT JOIN mc_catalog_cat_content AS cont ON ( c.id_cat = cont.id_cat )
						LEFT JOIN mc_lang AS lang ON ( cont.id_lang = lang.id_lang )
						WHERE cont.id_lang = :default_lang AND c.id_parent = :id';
					break;
				case 'catalog':
					$query = 'SELECT catalog.id_catalog, catalog.id_product, p_cont.name_p, catalog.order_p, lang.id_lang,lang.iso_lang
						FROM mc_catalog AS catalog
						JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
						JOIN mc_catalog_product_content AS p_cont ON ( p_cont.id_product = p.id_product )
						JOIN mc_lang AS lang ON ( p_cont.id_lang = lang.id_lang ) 
						WHERE catalog.id_cat = :id_cat AND p_cont.id_lang = :default_lang
						ORDER BY catalog.order_p';
					break;
				case 'lastCats':
					$query = "SELECT p.id_cat, c.name_cat, p.date_register
						FROM mc_catalog_cat AS p
						JOIN mc_catalog_cat_content AS c USING ( id_cat )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE c.id_lang = :default_lang
						ORDER BY p.id_cat DESC
						LIMIT 5";
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
				$query = "INSERT INTO `mc_catalog_cat`(id_parent,menu_cat,order_cat,date_register) 
						SELECT :id_parent,:menu_cat,count(order_cat),NOW() FROM mc_catalog_cat WHERE id_parent $cond ORDER BY order_cat desc LIMIT 0,1";
				break;
			case 'content':
				$query = 'INSERT INTO `mc_catalog_cat_content`(id_cat,id_lang,name_cat,url_cat,resume_cat,content_cat,link_label_cat,link_title_cat,seo_title_cat,seo_desc_cat,published_cat) 
				  		VALUES (:id_cat,:id_lang,:name_cat,:url_cat,:resume_cat,:content_cat,:link_label_cat,:link_title_cat,:seo_title_cat,:seo_desc_cat,:published_cat)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
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
                            link_label_cat = :link_label_cat, 
                            link_title_cat = :link_title_cat, 
                            seo_title_cat = :seo_title_cat, 
                            seo_desc_cat = :seo_desc_cat, 
                            published_cat = :published_cat
						WHERE id_cat = :id_cat AND id_lang = :id_lang';
				break;
			case 'img':
				$query = 'UPDATE mc_catalog_cat SET img_cat = :img_cat
                		WHERE id_cat = :id_cat';
				break;
			case 'imgContent':
				$query = 'UPDATE mc_catalog_cat_content 
						SET 
							alt_img = :alt_img,
							title_img = :title_img,
							caption_img = :caption_img
                		WHERE id_cat = :id_cat 
                		AND id_lang = :id_lang';
				break;
			case 'order':
				$query = 'UPDATE mc_catalog_cat SET order_cat = :order_cat
                		WHERE id_cat = :id_cat';
				break;
			case 'order_p':
				$query = 'UPDATE mc_catalog SET order_p = :order_p
                		WHERE id_catalog = :id_catalog';
				break;
			case 'pageActiveMenu':
				$query = 'UPDATE mc_catalog_cat 
						SET menu_cat = :menu_cat 
						WHERE id_cat IN ('.$params['id_cat'].')';
				$params = array(
					'menu_cat' => $params['menu_cat']
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

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
		switch ($config['type']) {
			case 'delPages':
				$query = 'DELETE FROM mc_catalog_cat WHERE id_cat IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delProduct':
				$query = 'DELETE FROM mc_catalog WHERE id_catalog IN ('.$params['id'].')';
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