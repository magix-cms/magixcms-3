<?php
class backend_db_product {
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
		$dateFormat = new component_format_date();

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$cond = '';
					$limit = '';
					if($config['offset']) {
						$limit = ' LIMIT 0, '.$config['offset'];
						if(isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
						}
					}

					if (isset($config['search'])) {
						if (is_array($config['search']) && !empty($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if ($q !== '') {
									$cond .= ' AND ';
									$p = 'p'.$nbc;
									switch ($key) {
										case 'id_product':
											$cond .= 'p.'.$key.' = :'.$p.' ';
											break;
										case 'published_p':
											$cond .= 'p.'.$key.' = :'.$p.' ';
											break;
										case 'name_p':
											$cond .= "pc.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'name_cat':
											$cond .= "cc.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'reference_p':
											$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
                                        case 'date_register':
                                            $q = $dateFormat->date_to_db_format($q);
                                            $cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
                                            break;

									}

                                    if(isset($params['search']) && is_array($params['search'])) {
                                        $newSearch = [];
                                        foreach ($params['search'] as $newKey => $value) {
                                            $newSearch = array_merge($newSearch, $value);
                                        }
                                        foreach ($newSearch as $search) {
                                            if($key == $search['key']){
                                                switch ($search['type']) {
                                                    case 'string':
                                                        $cond .= $search['as'] . "." . $key . " LIKE CONCAT('%', :" . $p . ", '%') ";
                                                        break;
                                                }
                                            }
                                        }
                                    }
									$params[$p] = $q;
									$nbc++;
								}
							}

						}
					}
					/*$query = "SELECT
								p.id_product, 
								pc.name_p, 
								cc.name_cat, 
								p.reference_p,
								p.price_p, 
								pc.resume_p , 
								pc.content_p,
								pc.seo_title_p, 
								pc.seo_desc_p,
								IFNULL(pi.default_img,0) as img_p,
								p.date_register
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							LEFT JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							LEFT JOIN mc_catalog_cat_content AS cc ON ( c.id_cat = cc.id_cat )
							LEFT JOIN mc_catalog_product_img AS pi ON ( p.id_product = pi.id_product AND pi.default_img = 1 )
							JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang )
							WHERE pc.id_lang = :default_lang $cond
							GROUP BY p.id_product 
							ORDER BY p.id_product DESC".$limit;*/

                    $where = '';
                    if(isset($params['where']) && is_array($params['where'])) {
                        $newWhere = [];

                        foreach ($params['where'] as $key => $value) {
                            $newWhere = array_merge($newWhere, $value);
                        }
                        foreach ($newWhere as $item) {
                            $where .= ' '.$item['type'].' '.$item['condition'].' ';
                        }
                        unset($params['where']);
                    }

                    $select = [
                        'p.id_product',
                        'pc.name_p',
                        'cc.name_cat',
                        'p.reference_p',
                        'p.price_p',
                        'pc.resume_p' ,
                        'pc.content_p',
                        'pc.link_label_p',
                        'pc.link_title_p',
                        'pc.seo_title_p',
                        'pc.seo_desc_p',
                        'IFNULL(pi.default_img,0) as default_img',
                        'p.date_register'
					];

                    if(isset($params['select'])) {
                        foreach ($params['select'] as $extendSelect) {
                            $select = array_merge($select, $extendSelect);
                        }
                        unset($params['select']);
                    }

                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];

                        foreach ($params['join'] as $key => $value) {
                            $newJoin = array_merge($newJoin, $value);
                        }
                        foreach ($newJoin as $join) {
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
                        }
                        unset($params['join']);
                    }

                    if(isset($params['search']) && is_array($params['search'])) {
                        unset($params['search']);
                    }

                    /*$query = 'SELECT '.implode(',', $select).'
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							LEFT JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							LEFT JOIN mc_catalog_cat_content AS cc ON ( c.id_cat = cc.id_cat )
							LEFT JOIN mc_catalog_product_img AS pi ON ( p.id_product = pi.id_product AND pi.default_img = 1 )
							JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) 
							'.$joins. 'WHERE pc.id_lang = :default_lang '.$cond.$where.' GROUP BY p.id_product 
							ORDER BY p.id_product DESC'. $limit;*/

                    $query = 'SELECT '.implode(',', $select).'
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							LEFT JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							LEFT JOIN mc_catalog_cat_content AS cc ON ( c.id_cat = cc.id_cat AND pc.id_lang = cc.id_lang )
							LEFT JOIN mc_catalog_product_img AS pi ON ( p.id_product = pi.id_product AND pi.default_img = 1 )
							'.$joins. 'WHERE pc.id_lang = :default_lang '.$cond.$where.' 
							ORDER BY p.id_product DESC'. $limit;
                    break;
				case 'page':
					$query = 'SELECT p.*,c.*,lang.*
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS c USING(id_product)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_product = :edit';
					break;
				case 'images':
					$query = 'SELECT img.*
						FROM mc_catalog_product_img AS img
						WHERE img.id_product = :id ORDER BY order_img';
					break;
				case 'imagesAll':
					$query = 'SELECT img.* FROM mc_catalog_product_img AS img';
					break;
				case 'catRel':
					$query = 'SELECT id_product, id_cat, default_c FROM mc_catalog WHERE id_product = :id';
					break;
				case 'productRel':
					$query = 'SELECT rel.*,c.name_p
							FROM mc_catalog_product_rel AS rel
							JOIN mc_catalog_product_content AS c ON(rel.id_product_2 = c.id_product)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE rel.id_product = :id AND c.id_lang = :default_lang';
					break;
				case 'imgData':
					$query = 'SELECT img.id_img,img.id_product, img.name_img,c.id_lang,c.alt_img,c.title_img,c.caption_img,lang.iso_lang
							FROM mc_catalog_product_img AS img
							LEFT JOIN mc_catalog_product_img_content AS c USING(id_img)
							LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE img.id_img = :edit';
					break;
				case 'lastProducts':
					$query = "SELECT p.id_product, c.name_p, p.date_register
						FROM mc_catalog_product AS p
						JOIN mc_catalog_product_content AS c USING ( id_product )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE c.id_lang = :default_lang
						GROUP BY p.id_product 
						ORDER BY p.id_product DESC
						LIMIT 5";
					break;
				case 'pagesPublishedSelect':
					$query = "SELECT 
								p.id_product,
								pc.name_p as name_product,
								cc.id_cat as id_parent,
								ccc.name_cat as name_parent,
								lang.iso_lang
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							JOIN mc_catalog_cat AS cc ON c.id_cat = cc.id_cat
							JOIN mc_catalog_cat_content AS ccc ON (cc.id_cat = ccc.id_cat AND pc.id_lang = ccc.id_lang)
							JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
							WHERE pc.id_lang = :default_lang
							AND pc.published_p = 1
							ORDER BY cc.id_cat,p.id_product";
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
					$query = 'SELECT id_product FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
					break;
				case 'page':
					$query = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id_product';
					break;
				case 'rootImg':
					$query = 'SELECT id_img FROM mc_catalog_product_img WHERE id_product = :id ORDER BY id_img DESC LIMIT 0,1';
					break;
				case 'imgContent':
					$query = 'SELECT * FROM mc_catalog_product_img_content WHERE `id_img` = :id_img AND `id_lang` = :id_lang';
					break;
				case 'img':
					$query = 'SELECT * FROM mc_catalog_product_img WHERE `id_img` = :id';
					break;
				case 'lastImgId':
					$query = 'SELECT id_img as `index` FROM mc_catalog_product_img WHERE id_product = :id_product ORDER BY id_img DESC LIMIT 0,1';
					break;
				case 'imgDefault':
					$query = 'SELECT id_img FROM mc_catalog_product_img WHERE id_product = :id AND default_img = 1';
					break;
				case 'catRel':
					$query = 'SELECT * FROM mc_catalog WHERE id_product = :id AND id_cat = :id_cat';
					break;
				case 'lastProductRel':
					$query = 'SELECT rel.*,c.name_p
						FROM mc_catalog_product_rel AS rel
						JOIN mc_catalog_product_content AS c ON(rel.id_product_2 = c.id_product)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE rel.id_product = :id AND c.id_lang = :default_lang
						ORDER BY rel.id_rel DESC LIMIT 0,1';
					break;
				case 'pageLang':
					$query = 'SELECT 
								p.id_product,
								pc.name_p,
								pc.url_p,
								pc.link_label_p,
								pc.link_title_p,
								cc.id_cat as id_parent,
								ccc.name_cat as name_parent,
								ccc.url_cat as url_parent,
								lang.iso_lang
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							JOIN mc_catalog_cat AS cc ON c.id_cat = cc.id_cat
							JOIN mc_catalog_cat_content AS ccc ON (cc.id_cat = ccc.id_cat AND pc.id_lang = ccc.id_lang)
							JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
							WHERE p.id_product = :id
							AND lang.iso_lang = :iso';
					break;
				case 'countImages':
					$query = 'SELECT count(id_img) as tot FROM mc_catalog_product_img WHERE id_product = :id';
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
			case 'newPages':
				$query = 'INSERT INTO `mc_catalog_product`(price_p,reference_p,date_register) 
						VALUES (:price_p,:reference_p,NOW())';
				break;
			case 'newContent':
				$query = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,longname_p,url_p,resume_p,content_p,seo_title_p,seo_desc_p,published_p) 
			  			VALUES (:id_product,:id_lang,:name_p,:longname_p,:url_p,:resume_p,:content_p,:seo_title_p,:seo_desc_p,:published_p)';
				break;
			case 'newImg':
				$query = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img,order_img,default_img) 
						SELECT :id_product,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_catalog_product_img WHERE id_product IN ('.$params['id_product'].')';
				break;
			case 'newImgContent':
				$query = 'INSERT INTO `mc_catalog_product_img_content`(id_img,id_lang,alt_img,title_img,caption_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img,:caption_img)';
				break;
			case 'catRel':
				$query = 'INSERT INTO `mc_catalog` (id_product,id_cat,default_c,order_p)
						SELECT :id,:id_cat,:default_c,COUNT(id_catalog) FROM mc_catalog WHERE id_cat IN ('.$params['id_cat'].')';
				break;
			case 'productRel':
				$query = 'INSERT INTO `mc_catalog_product_rel` (id_product,id_product_2)
					VALUES (:id_product,:id_product_2)';
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
			case 'product':
				$query = 'UPDATE mc_catalog_product SET price_p = :price_p, reference_p = :reference_p
                WHERE id_product = :id_product';
				break;
			case 'content':
				$query = 'UPDATE mc_catalog_product_content 
						SET 
							name_p = :name_p,
							longname_p = :longname_p,
							url_p = :url_p,
							resume_p = :resume_p,
							content_p = :content_p,
							link_label_p = :link_label_p,
							link_title_p = :link_title_p,
							seo_title_p = :seo_title_p, 
                            seo_desc_p = :seo_desc_p,
							published_p = :published_p
							WHERE id_product = :id_product 
                		AND id_lang = :id_lang';
				break;
            case 'properties':
                $query = 'UPDATE mc_catalog_product SET 
                          width_p = :width_p, 
                          weight_p = :weight_p, 
                          depth_p = :depth_p, 
                          height_p= :height_p
                        WHERE id_product = :id_product';
                break;
			case 'imgContent':
				$query = 'UPDATE mc_catalog_product_img_content 
						SET 
							alt_img = :alt_img, 
							title_img = :title_img,
							caption_img = :caption_img
                		WHERE id_img = :id_img AND id_lang = :id_lang';
				break;
			case 'img':
				$query = 'UPDATE mc_catalog_product_img 
						SET 
							name_img = :name_img
                		WHERE id_img = :id_img';
				break;
			case 'catRel':
				$query = 'UPDATE mc_catalog
                		SET default_c = CASE id_cat
							WHEN :id_cat THEN 1
							ELSE 0
						END
						WHERE id_product = :id';
				break;
			case 'imageDefault':
				$query = 'UPDATE mc_catalog_product_img
                		SET default_img = CASE id_img
							WHEN :id_img THEN 1
							ELSE 0
						END
						WHERE id_product = :id';
				break;
			case 'firstImageDefault':
				$query = 'UPDATE mc_catalog_product_img
                		SET default_img = 1
                		WHERE id_product = :id 
						ORDER BY order_img 
						LIMIT 1';
				break;
			case 'order':
				$query = 'UPDATE mc_catalog_product_img SET order_img = :order_img
                		WHERE id_img = :id_img';
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
				$query = 'DELETE FROM `mc_catalog_product` WHERE `id_product` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delImages':
				$query = 'DELETE FROM `mc_catalog_product_img` WHERE `id_img` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'catRel':
				$query = 'DELETE FROM mc_catalog WHERE id_product = :id';
				break;
			case 'oldCatRel':
				$query = 'DELETE FROM mc_catalog WHERE id_product = '.$params['id'].' AND id_cat NOT IN ('.$params['id_cat'].')';
				$params = array();
				break;
			case 'productRel':
				$query = 'DELETE FROM `mc_catalog_product_rel` WHERE `id_rel` IN ('.$params['id'].')';
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