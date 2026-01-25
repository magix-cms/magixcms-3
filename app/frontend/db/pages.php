<?php
class frontend_db_pages {
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
				case 'langs':
					$query = 'SELECT
								h.*,
								c.name_pages,
								c.url_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
								COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
								lang.id_lang,
								lang.iso_lang
							FROM mc_cms_page AS h
							JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND c.published_pages = 1';
					break;
				/*case 'pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT
								p.*,
								c.name_pages,
								c.url_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
								img.name_img,
								COALESCE(imgc.alt_img, c.name_pages) as alt_img,
								COALESCE(imgc.title_img, imgc.alt_img, c.name_pages) as title_img,
								COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, c.name_pages) as caption_img,
								COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
								lang.id_lang,
								lang.iso_lang,
								lang.default_lang
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
							LEFT JOIN mc_cms_page_img AS img ON (p.id_pages = img.id_pages)
							LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							$conditions";
					break;*/
                case 'pages':
                    $where = '';
                    if(isset($params['where']) && is_array($params['where'])) {
                        $newWhere = [];
                        foreach ($params['where'] as $value) { $newWhere = array_merge($newWhere, $value); }
                        foreach ($newWhere as $item) { $where .= ' '.$item['type'].' '.$item['condition'].' '; }
                        unset($params['where']);
                    }
                    $select = [
                        'p.*',
                        'pc.name_pages',
                        'pc.url_pages',
                        'pc.link_label_pages',
                        'pc.link_title_pages',
                        'pc.resume_pages',
                        'pc.content_pages',
                        'pc.published_pages',
                        'img.name_img',
                        'COALESCE(imgc.alt_img, pc.name_pages) as alt_img',
                        'COALESCE(imgc.title_img, imgc.alt_img, pc.name_pages) as title_img',
                        'COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, pc.name_pages) as caption_img',
                        'pc.seo_title_pages',
                        'pc.seo_desc_pages',
                        'childs.childs',
                        'lang.id_lang',
                        'lang.iso_lang',
                        'lang.default_lang'
                    ];
                    if(isset($params['select'])) {
                        foreach ($params['select'] as $extendSelect) { $select = array_merge($select, $extendSelect); }
                        unset($params['select']);
                    }
                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];
                        foreach ($params['join'] as $value) { $newJoin = array_merge($newJoin, $value); }
                        foreach ($newJoin as $join) {
                            $key = isset($join['on']['newkey']) ? $join['on']['newkey'] : $join['on']['key'];
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$key .') ';
                        }
                        unset($params['join']);
                    }
                    $listids = '';
                    $order = ''; // Chaîne finale pour le SQL

                    if (isset($params['listids']) && !empty($params['listids'])) {
                        $ids = explode(',', $params['listids']);
                        $in_placeholders = [];
                        $field_placeholders = [];

                        foreach ($ids as $index => $id) {
                            $val = trim($id);

                            // 1. Marqueurs pour le filtrage (IN)
                            $in_key = 'id_in_' . $index;
                            $in_placeholders[] = ':' . $in_key;
                            $params[$in_key] = $val;

                            if (!isset($params['order'])) {
                                // 2. Marqueurs pour le tri (FIELD) - CLÉS DIFFÉRENTES
                                $fld_key = 'id_fld_' . $index;
                                $field_placeholders[] = ':' . $fld_key;
                                $params[$fld_key] = $val;
                            }
                        }

                        // On construit la clause IN
                        $listids = 'AND p.id_pages IN (' . implode(',', $in_placeholders) . ') ';

                        // 3. On définit l'ordre directement si aucun ordre n'est passé
                        // On évite ainsi de toucher au tableau $params['order'] qui fait planter la boucle
                        if (!isset($params['order'])) {
                            $order = ' ORDER BY FIELD(p.id_pages, ' . implode(',', $field_placeholders) . ')';
                        }

                        unset($params['listids']);
                    }

                    if (empty($order)) {
                        if (!isset($params['order']) || !is_array($params['order'])) {
                            $order = ' ORDER BY p.order_pages';
                        } else {
                            $order = ' ORDER BY ';
                            $orders = [];
                            foreach ($params['order'] as $extendOrder) {
                                if (is_array($extendOrder)) {
                                    $orders = array_merge($orders, $extendOrder);
                                } else {
                                    $orders[] = (string)$extendOrder;
                                }
                            }
                            $order .= implode(',', $orders);
                            unset($params['order']);
                        }
                    }

                    $limit = '';
                    if(isset($params['limit']) && is_array($params['limit'])){
                        foreach ($params['limit'] as $item) { $limit = ' LIMIT '.$item; }
                        unset($params['limit']);
                    }

                    $query = 'SELECT '.implode(',', $select)."
						FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS pc ON(p.id_pages = pc.id_pages) 
							LEFT JOIN (
                                SELECT GROUP_CONCAT( id_pages SEPARATOR ',' ) AS childs, id_parent FROM mc_cms_page WHERE id_parent IS NOT NULL GROUP BY id_parent
                            ) as childs ON (p.id_pages = childs.id_parent)
							LEFT JOIN mc_cms_page_img AS img ON (p.id_pages = img.id_pages)
							LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and pc.id_lang = imgc.id_lang)
							JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang) ".$joins." WHERE lang.iso_lang = :iso AND pc.published_pages = 1 AND (img.default_img = 1 OR img.default_img IS NULL) "
                        .$where.$listids.$order.$limit;

                    break;
				case 'rand_pages':
					$queries = [
						['request' => 'CREATE TEMPORARY TABLE page_map (row_id int not NULL primary key, random_id int not null)'],
						['request' => 'CREATE TEMPORARY TABLE random_ids (rand_id int auto_increment not NULL primary key, gen_id int not null)'],
						['request' => 'SET @id = 0'],
						[
							'request' => '
								INSERT INTO page_map
								SELECT @id := @id + 1, p.id_pages 
								FROM mc_cms_page AS p 
								JOIN mc_cms_page_content AS pc ON ( p.id_pages = pc.id_pages )
								JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) WHERE pc.published_pages = 1 and lang.iso_lang = :iso',
							'params' => ['iso' => $params['iso']]
						],
						['request' => 'INSERT INTO random_ids (gen_id) VALUES '.$params['ids']],
						['request' => "SELECT rows.random_id FROM page_map as rows JOIN random_ids as ids ON(rows.row_id = ids.gen_id)", 'fetch'=>true]
					];

					try {
						$result = component_routing_db::layer()->transaction($queries);
						return $result[5];
					}
					catch (Exception $e) {
						if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
						$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
					}
					break;
				case 'pages_short':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT
								p.id_pages,
								p.id_parent,
								c.name_pages,
								c.url_pages,
								c.link_label_pages,
								c.link_title_pages,
								COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								lang.iso_lang
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							$conditions";
					break;
				case 'imgs':
					$query = 'SELECT 
						img.id_img,
						img.id_pages,
						img.name_img,
						COALESCE(c.alt_img, pc.name_pages) as alt_img,
						COALESCE(c.title_img, c.alt_img, pc.name_pages) as title_img,
						COALESCE(c.caption_img, c.title_img, c.alt_img, pc.name_pages) as caption_img,
						img.default_img,
						img.order_img,
						c.id_lang,
						lang.iso_lang
					FROM mc_cms_page AS p
					LEFT JOIN mc_cms_page_content AS pc ON (p.id_pages = pc.id_pages)
					LEFT JOIN mc_cms_page_img AS img ON (img.id_pages = p.id_pages)
					LEFT JOIN mc_cms_page_img_content AS c ON (img.id_img = c.id_img AND c.id_lang = pc.id_lang)
					LEFT JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
					WHERE img.id_pages = :id AND lang.iso_lang = :iso
					ORDER BY img.order_img';
					break;
				case 'child':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT 
								p.id_pages,
								p.id_parent,
								p.menu_pages, 
								p.date_register, 
								c.name_pages,
								c.url_pages,
								c.link_label_pages,
								c.link_title_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
								img.name_img,
								COALESCE(imgc.alt_img, c.name_pages) as alt_img,
								COALESCE(imgc.title_img, imgc.alt_img, c.name_pages) as title_img,
								COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, c.name_pages) as caption_img,
								COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
								lang.iso_lang
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
							LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
							LEFT JOIN mc_cms_page_img AS img ON (p.id_pages = img.id_pages AND img.default_img = 1)
							LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
							$conditions";
					break;
				case 'parents':
					$query = "SELECT t.id_pages AS parent, GROUP_CONCAT(f.id_pages) AS children
							FROM mc_cms_page t
							JOIN mc_cms_page f ON t.id_pages=f.id_parent
							GROUP BY t.id_pages";
					break;
				case 'ws':
					$query = 'SELECT
							h.*,c.*,lang.iso_lang,lang.default_lang
							FROM mc_cms_page AS h
							JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id';
					break;
				case 'images':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$query = "SELECT img.* FROM mc_cms_page_img AS img $conditions";
					break;
				case 'images_content':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$query = "SELECT c.*,lang.iso_lang
					FROM mc_cms_page_img_content AS c
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
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'page':
					$query = 'SELECT
								h.*,
								c.name_pages,
								c.url_pages,
								c.link_label_pages,
								c.link_title_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
								img.name_img,
								COALESCE(imgc.alt_img, c.name_pages) as alt_img,
								COALESCE(imgc.title_img, imgc.alt_img, c.name_pages) as title_img,
								COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, c.name_pages) as caption_img,
								COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
								lang.iso_lang,
								parent.name_pages as parent_name,
								parent.url_pages as parent_url,
								parent.seo_title_pages as seo_title_parent
							FROM mc_cms_page AS h
							JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
							LEFT JOIN mc_cms_page_img AS img ON (h.id_pages = img.id_pages AND img.default_img = 1)
							LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                            LEFT JOIN (
                                SELECT id_lang, id_pages, name_pages, url_pages, seo_title_pages FROM mc_cms_page_content WHERE published_pages = 1
                            ) as parent ON (h.id_parent = parent.id_pages AND parent.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND lang.iso_lang = :iso AND c.published_pages = 1';
					break;
                case 'pages':
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
                        'h.*',
                        'c.name_pages',
                        'c.longname_pages',
                        'c.url_pages',
                        'c.link_label_pages',
                        'c.link_title_pages',
                        'c.resume_pages',
                        'c.content_pages',
                        'c.published_pages',
                        'img.name_img',
                        'COALESCE(imgc.alt_img, c.name_pages) as alt_img',
                        'COALESCE(imgc.title_img, imgc.alt_img, c.name_pages) as title_img',
                        'COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, c.name_pages) as caption_img',
                        'COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages',
                        'COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages',
                        'lang.iso_lang',
                        'parent.name_pages as parent_name',
                        'parent.url_pages as parent_url',
                        'parent.seo_title_pages as seo_title_parent'
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
                    if(!isset($params['order']) || !is_array($params['order'])) {
                        $order = '';
                    }

                    if(isset($params['order']) && is_array($params['order'])){
                        $order = ' ORDER BY ';
                        $orders = [];

                        foreach ($params['order'] as $extendOrder) {
                            $orders = array_merge($orders, $extendOrder);
                        }

                        $order .= ' '.implode(',', $orders);

                        unset($params['order']);
                    }
                    $limit = '';
                    if(isset($params['limit']) && is_array($params['limit'])){

                        foreach ($params['limit'] as $item) {
                            $limit = ' LIMIT '.$item;
                        }
                        unset($params['limit']);
                    }
                    $query = 'SELECT '.implode(',', $select).'
                            FROM mc_cms_page AS h
							JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
							LEFT JOIN mc_cms_page_img AS img ON (h.id_pages = img.id_pages AND img.default_img = 1)
							LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                            LEFT JOIN (
                                SELECT id_lang, id_pages, name_pages, url_pages, seo_title_pages FROM mc_cms_page_content WHERE published_pages = 1
                            ) as parent ON (h.id_parent = parent.id_pages AND parent.id_lang = lang.id_lang) 
                            '.$joins.'
							WHERE h.id_pages = :id AND lang.iso_lang = :iso AND c.published_pages = 1 '.$where
                            .$order.$limit;

                    break;
				case 'root':
					$query = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
					break;
				case 'wsEdit':
					$query = 'SELECT * FROM mc_cms_page WHERE `id_pages` = :id';
					break;
				/*case 'image':
					$query = 'SELECT img_pages FROM mc_cms_page WHERE `id_pages` = :id_pages';
					break;*/
				case 'content':
					$query = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
					break;
				case 'pageLang':
					$query = 'SELECT p.*,c.*,lang.*
						FROM mc_cms_page AS p
						JOIN mc_cms_page_content AS c USING(id_pages)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE p.id_pages = :id
						AND lang.iso_lang = :iso';
					break;
				case 'tot_pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT COUNT(DISTINCT p.id_pages) as tot
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
							LEFT JOIN mc_cms_page_img AS img ON (p.id_pages = img.id_pages)
							LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				case 'lastImgId':
					$query = 'SELECT id_img FROM mc_cms_page_img ORDER BY id_img DESC LIMIT 0,1';
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
	 * @return bool
	 */
	public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
		    case 'page':
				$cond = $params['id_parent'] != NULL ? 'IN ('.$params['id_parent'].')' : 'IS NULL';
				$query = "INSERT INTO `mc_cms_page`(id_parent,order_pages,date_register) 
						SELECT :id_parent,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent $cond";
		    	break;
		    case 'content':
				$query = 'INSERT INTO `mc_cms_page_content`(id_pages,id_lang,name_pages,url_pages,resume_pages,content_pages,link_label_pages,link_title_pages,seo_title_pages,seo_desc_pages,published_pages) 
				  		VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:resume_pages,:content_pages,:link_label_pages,:link_title_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';
		    	break;
            case 'newImg':
                $query = 'INSERT INTO `mc_cms_page_img`(id_pages,name_img,order_img,default_img) 
						SELECT :id_pages,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_cms_page_img WHERE id_pages IN ('.$params['id_pages'].')';
                break;
            case 'newImgContent':
                $query = 'INSERT INTO `mc_cms_page_img_content`(id_img,id_lang,alt_img,title_img,caption_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img,:caption_img)';
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
			return false;
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'page':
				$query = 'UPDATE mc_cms_page 
							SET 
								id_parent = :id_parent,
								menu_pages = :menu_pages
							WHERE id_pages = :id_pages';
				break;
			case 'content':
				$query = 'UPDATE mc_cms_page_content 
						SET 
							name_pages = :name_pages,
							url_pages = :url_pages,
							resume_pages = :resume_pages,
							content_pages = :content_pages,
							link_label_pages = :link_label_pages,
							link_title_pages = :link_title_pages,
							seo_title_pages = :seo_title_pages,
							seo_desc_pages = :seo_desc_pages, 
							published_pages = :published_pages
                		WHERE id_pages = :id_pages 
                		AND id_lang = :id_lang';
				break;
			case 'pageActiveMenu':
				$query = 'UPDATE mc_cms_page 
						SET menu_pages = :menu_pages 
						WHERE id_pages IN ('.$params['id_pages'].')';
				$params = array(
					':menu_pages'	=> $params['menu_pages']
				);
				break;
			case 'order':
				$query = 'UPDATE mc_cms_page 
						SET order_pages = :order_pages
                		WHERE id_pages = :id_pages';
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
			return false;
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	public function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'delPages':
				$query = 'DELETE FROM `mc_cms_page` WHERE `id_pages` IN ('.$params['id'].')';
				$params = array();
				break;
            case 'delImages':
                $query = 'DELETE FROM `mc_cms_page_img` WHERE `id_img` IN ('.$params['id'].')';
                $params = array();
                break;
            case 'delImagesAll':
                $query = 'DELETE FROM `mc_cms_page_img` WHERE id_pages = :id';
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
			return false;
		}
    }
}