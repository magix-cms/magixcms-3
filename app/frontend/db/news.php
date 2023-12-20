<?php
class frontend_db_news {
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
								p.*,
								c.name_news,
								c.url_news,
								c.resume_news,
								c.content_news,
								c.published_news,
								COALESCE(c.alt_img, c.name_news) as alt_img,
								COALESCE(c.title_img, c.alt_img, c.name_news) as title_img,
								COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_news) as caption_img,
								c.seo_title_news,
								c.seo_desc_news,
								lang.id_lang,
								lang.iso_lang,
								lang.default_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_news = :id AND c.published_news = 1';
			    	break;
			    case 'pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$query = "SELECT 
								p.*,
								c.name_news,
								c.url_news,
								c.resume_news,
								c.content_news,
								c.published_news,
								COALESCE(c.alt_img, c.name_news) as alt_img,
								COALESCE(c.title_img, c.alt_img, c.name_news) as title_img,
								COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_news) as caption_img,
								c.link_label_news,
								c.link_title_news,
								c.seo_title_news,
								c.seo_desc_news,
								lang.id_lang,
								lang.iso_lang,
								lang.default_lang,
								tagrel.tags_ids
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							    LEFT JOIN (
							        SELECT mn.id_news, GROUP_CONCAT(mntr.id_tag SEPARATOR ',') as tags_ids FROM mc_news as mn LEFT JOIN mc_news_tag_rel as mntr on (mn.id_news = mntr.id_news) GROUP BY mn.id_news
								) tagrel ON(tagrel.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							$conditions";
			    	break;
			    case 'pages_short':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT 
								p.id_news,
								c.name_news,
								c.url_news,
								c.link_label_news,
								c.link_title_news,
								p.date_publish,
								c.seo_title_news,
								lang.iso_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							$conditions";
			    	break;
			    case 'tagsRel':
					$query = 'SELECT tag.id_tag,tag.name_tag,lang.iso_lang
						FROM mc_news_tag AS tag
						JOIN mc_news_tag_rel AS tagrel USING ( id_tag )
						JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
						WHERE tagrel.id_news = :id AND lang.iso_lang = :iso';
			    	break;
			    case 'tagsLang':
					$query = "SELECT tag.id_tag,tag.name_tag,lang.iso_lang
						FROM mc_news_tag AS tag
						JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
						WHERE lang.iso_lang = :iso";
			    	break;
			    case 'tags':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT tag.id_tag,tag.name_tag,lang.iso_lang
						FROM mc_news_tag AS tag
						JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang) 
						$conditions";
			    	break;
			    case 'archives':
					/*$query = "SELECT GROUP_CONCAT(DISTINCT MONTH(`date_publish`)) AS mths, YEAR(`date_publish`) AS yr
						FROM mc_news AS news
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang USING(id_lang)
						WHERE c.published_news = 1
						AND lang.iso_lang = :iso
						GROUP BY YEAR(date_publish)
						ORDER BY date_publish DESC";*/
                    $query = "SELECT
                                MONTH(`date_publish`) AS month, 
                                YEAR(`date_publish`) AS year,
                                COUNT(`id_news`) AS number
                            FROM mc_news AS news
                            JOIN mc_news_content AS c USING(id_news)
                            JOIN mc_lang AS lang USING(id_lang)
                            WHERE c.published_news = 1
                                AND lang.iso_lang = :iso
                            GROUP BY YEAR(date_publish), MONTH(date_publish)
                            ORDER BY date_publish DESC";
			    	break;
			    case 'ws':
					$query = 'SELECT p.img_news,c.*,lang.iso_lang,lang.default_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
						WHERE p.id_news = :id';
			    	break;
				case 'news':
					$where = '';
					if(isset($params['where']) && is_array($params['where'])) {
						$newWhere = [];

						foreach ($params['where'] as $value) {
							$newWhere = array_merge($newWhere, $value);
						}
						foreach ($newWhere as $item) {
							$where .= ' '.$item['type'].' '.$item['condition'].' ';
						}
						unset($params['where']);
					}

					$select = [
						'mn.*',
						'mnc.name_news',
						'mnc.url_news',
						'mnc.resume_news',
						'mnc.content_news',
						'mnc.published_news',
						//'mn.date_publish',
                        'img.name_img',
                        'COALESCE(imgc.alt_img, mnc.name_news) as alt_img',
                        'COALESCE(imgc.title_img, imgc.alt_img, mnc.name_news) as title_img',
                        'COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, mnc.name_news) as caption_img',
						'mnc.link_label_news',
						'mnc.link_title_news',
						'mnc.seo_title_news',
						'mnc.seo_desc_news',
						'ml.id_lang',
						'ml.iso_lang',
						'ml.default_lang',
                        'tagrel.tags_ids'
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

						foreach ($params['join'] as $value) {
							$newJoin = array_merge($newJoin, $value);
						}
						foreach ($newJoin as $join) {
							//$joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
							if(is_array($join)) {
								$joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'];
								if(isset($join['on'])) {
									if(is_array($join['on'])) {
										$joins .= ' ON '.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'];
									}
									if(is_string($join['on'])) {
										$joins .= ' ON '.$join['on'];
									}
								}
								if(isset($join['using']) && is_string($join['using'])) {
									$joins .= ' USING ('.$join['using'].')';
								}
							}
							if(is_string($join)) $joins .= ' '.$join;
						}

						unset($params['join']);
					}

					$group = '';
					if(isset($params['group']) && is_array($params['group'])) {
						$group = ' GROUP BY ';
						$groups = [];

						foreach ($params['group'] as $extendGroup) {
							if(!is_array($extendGroup)) {
								if(!in_array($extendGroup,$groups)) $groups[] = $extendGroup;
							}
							else {
								foreach ($extendGroup as $extendGroupValue) {
									if(!in_array($extendGroupValue,$groups)) $groups[] = $extendGroupValue;
								}
							}
							//if(!in_array($extendGroup,$groups)) $groups = array_merge($groups, $extendGroup);
						}

						$group .= ' '.implode(',', $groups);

						if(isset($params['having']) && is_array($params['having'])) {
							$having = ' HAVING ';
							$havings = [];
							foreach ($params['having'] as $extendHaving) {
								$havings = array_merge($havings, $extendHaving);
							}
							$group .= $having.' '.implode(' AND ', $havings);
							unset($params['having']);
						}

						unset($params['group']);
					}

					$order = '';
					if(isset($params['order']) && is_array($params['order'])) {
						$order = ' ORDER BY ';
						$orders = [];

						foreach ($params['order'] as $extendOrder) {
							$orders = array_merge($orders, $extendOrder);
						}

						$order .= ' '.implode(',', $orders);

						unset($params['order']);
					}
					elseif(!isset($params['order']) || !is_array($params['order'])) {
						$order = ' ORDER BY mn.date_publish DESC, mn.id_news DESC';
					}

					$limit = '';
					if(isset($params['limit']) && is_array($params['limit'])) {
						foreach ($params['limit'] as $item) {
							$limit = ' LIMIT '.$item;
						}
						unset($params['limit']);
					}

                    $query = "SELECT ".implode(',', $select)."
						FROM mc_news mn
							JOIN mc_news_content mnc ON (mn.id_news = mnc.id_news)
							LEFT JOIN mc_news_img AS img ON (mn.id_news = img.id_news)
							LEFT JOIN mc_news_img_content AS imgc ON (imgc.id_img = img.id_img AND mnc.id_lang = imgc.id_lang)
							LEFT JOIN (
							        SELECT mn.id_news, GROUP_CONCAT(mntr.id_tag SEPARATOR ',') as tags_ids FROM mc_news as mn LEFT JOIN mc_news_tag_rel as mntr on (mn.id_news = mntr.id_news) GROUP BY mn.id_news
								) tagrel ON(tagrel.id_news = mn.id_news)
							JOIN mc_lang ml ON mnc.id_lang = ml.id_lang
							".$joins."
						WHERE ml.iso_lang = :iso 
							AND mnc.published_news = 1 AND (img.default_img = 1 OR img.default_img IS NULL) 
						 ".$where
						.$group
						.$order
						.$limit;
					break;
                case 'imgs':
                    $query = 'SELECT 
						img.id_img,
						img.id_news,
						img.name_img,
						COALESCE(c.alt_img, pc.name_news) as alt_img,
						COALESCE(c.title_img, c.alt_img, pc.name_news) as title_img,
						COALESCE(c.caption_img, c.title_img, c.alt_img, pc.name_news) as caption_img,
						img.default_img,
						img.order_img,
						c.id_lang,
						lang.iso_lang
					FROM mc_news AS p
					LEFT JOIN mc_news_content AS pc ON (p.id_news = pc.id_news)
					LEFT JOIN mc_news_img AS img ON (img.id_news = p.id_news)
					LEFT JOIN mc_news_img_content AS c ON (img.id_img = c.id_img AND c.id_lang = pc.id_lang)
					LEFT JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
					WHERE img.id_news = :id AND lang.iso_lang = :iso
					ORDER BY img.order_img';
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
                    //COALESCE(c.alt_img, c.name_news) as alt_img,
                    //								COALESCE(c.title_img, c.alt_img, c.name_news) as title_img,
                    //								COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_news) as caption_img,
					$query = "SELECT 
								p.id_news,
								c.name_news,
								c.longname_news,
								c.url_news,
								c.resume_news,
								c.content_news,
								c.published_news,
								p.date_register,
								p.date_publish,
								p.date_event_start,
								p.date_event_end,
								c.link_label_news,
								c.link_title_news,
								c.seo_title_news,
								c.seo_desc_news,
								lang.iso_lang,
								tagrel.tags_ids
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
                            LEFT JOIN (
                                SELECT mn.id_news, GROUP_CONCAT(mntr.id_tag SEPARATOR ',') as tags_ids FROM mc_news as mn LEFT JOIN mc_news_tag_rel as mntr on (mn.id_news = mntr.id_news) GROUP BY mn.id_news
                            ) tagrel ON(tagrel.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
							WHERE p.id_news = :id AND lang.iso_lang = :iso AND c.published_news = 1";
			    	break;
			    case 'nb_archives':
					$query = "SELECT COUNT(`id_news`) AS nbr
						FROM mc_news AS news
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang USING(id_lang)
						WHERE c.published_news = 1
						AND lang.iso_lang = :iso
						AND YEAR(date_publish) = :yr
						AND MONTH(date_publish) = :mth";
			    	break;
			    case 'tag':
					$query = "SELECT id_tag as id, name_tag as name FROM mc_news_tag WHERE id_tag = :id";
			    	break;
				// Web Service
			    case 'root':
					$query = 'SELECT * FROM mc_news ORDER BY id_news DESC LIMIT 0,1';
			    	break;
			    case 'wsEdit':
					$query = 'SELECT * FROM mc_news WHERE `id_news` = :id';
			    	break;
			    case 'image':
					$query = 'SELECT img_news FROM mc_news WHERE `id_news` = :id_news';
			    	break;
			    case 'content':
					$query = 'SELECT * FROM `mc_news_content` WHERE `id_news` = :id_news AND `id_lang` = :id_lang';
			    	break;
			    case 'tag_ws':
					$query = 'SELECT tag.*, (SELECT id_rel FROM mc_news_tag_rel WHERE id_news = :id_news AND id_tag = tag.id_tag) AS rel_tag
							FROM mc_news_tag AS tag
							WHERE tag.id_lang = :id_lang AND tag.name_tag LIKE :name_tag';
			    	break;
			    case 'prev_page':
					$query = "SELECT c.id_news,
       							c.name_news,
								c.url_news,
								c.resume_news,
								c.content_news,
								c.published_news,
								p.date_publish,
								COALESCE(c.alt_img, c.name_news) as alt_img,
								COALESCE(c.title_img, c.alt_img, c.name_news) as title_img,
								COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_news) as caption_img,
								c.seo_title_news,
								c.seo_desc_news,
								lang.iso_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
							WHERE lang.iso_lang = :iso AND c.published_news = 1
							AND (c.date_publish < :date_publish OR (c.date_publish = '".$params['date_publish']."' AND p.id_news < :id))
							ORDER BY c.date_publish DESC LIMIT 1";
			    	break;
			    case 'next_page':
					$query = "SELECT c.id_news,
       							c.name_news,
								c.url_news,
								c.resume_news,
								c.content_news,
								c.published_news,
								p.date_publish,
								COALESCE(c.alt_img, c.name_news) as alt_img,
								COALESCE(c.title_img, c.alt_img, c.name_news) as title_img,
								COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_news) as caption_img,
								c.seo_title_news,
								c.seo_desc_news,
								lang.iso_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
							WHERE lang.iso_lang = :iso AND c.published_news = 1
							AND (c.date_publish > :date_publish OR (c.date_publish = '".$params['date_publish']."' AND p.id_news > :id))
							ORDER BY c.date_publish LIMIT 1";
			    	break;
				case 'count_news':
					$where = '';
					if(isset($params['where']) && is_array($params['where'])) {
						$newWhere = [];

						foreach ($params['where'] as $value) {
							$newWhere = array_merge($newWhere, $value);
						}
						foreach ($newWhere as $item) {
							$where .= ' '.$item['type'].' '.$item['condition'].' ';
						}
						unset($params['where']);
					}

					$joins = '';
					if(isset($params['join']) && is_array($params['join'])) {
						$newJoin = [];

						foreach ($params['join'] as $value) {
							$newJoin = array_merge($newJoin, $value);
						}
						foreach ($newJoin as $join) {
							//$joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
							if(is_array($join)) {
								$joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'];
								if(isset($join['on'])) {
									if(is_array($join['on'])) {
										$joins .= ' ON '.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'];
									}
									if(is_string($join['on'])) {
										$joins .= ' ON '.$join['on'];
									}
								}
								if(isset($join['using']) && is_string($join['using'])) {
									$joins .= ' USING ('.$join['using'].')';
								}
							}
							if(is_string($join)) $joins .= ' '.$join;
						}

						unset($params['join']);
					}

					$group = '';
					if(isset($params['group']) && is_array($params['group'])) {
						$group = ' GROUP BY ';
						$groups = [];

						foreach ($params['group'] as $extendGroup) {
							if(!is_array($extendGroup)) {
								if(!in_array($extendGroup,$groups)) $groups[] = $extendGroup;
							}
							else {
								foreach ($extendGroup as $extendGroupValue) {
									if(!in_array($extendGroupValue,$groups)) $groups[] = $extendGroupValue;
								}
							}
							//if(!in_array($extendGroup,$groups)) $groups = array_merge($groups, $extendGroup);
						}

						$group .= ' '.implode(',', $groups);

						if(isset($params['having']) && is_array($params['having'])) {
							$having = ' HAVING ';
							$havings = [];
							foreach ($params['having'] as $extendHaving) {
								$havings = array_merge($havings, $extendHaving);
							}
							$group .= $having.' '.implode(' AND ', $havings);
							unset($params['having']);
						}

						unset($params['group']);
					}

					if(!isset($params['order']) || !is_array($params['order'])) $order = ' ORDER BY mn.date_publish DESC, mn.id_news DESC';

					if(isset($params['order']) && is_array($params['order'])) {
						$order = ' ORDER BY ';
						$orders = [];

						foreach ($params['order'] as $extendOrder) {
							$orders = array_merge($orders, $extendOrder);
						}

						$order .= ' '.implode(',', $orders);

						unset($params['order']);
					}

					$limit = '';
					if(isset($params['limit']) && is_array($params['limit'])) {
						foreach ($params['limit'] as $item) {
							$limit = ' LIMIT '.$item;
						}
						unset($params['limit']);
					}

					$query = 'SELECT COUNT(mn.id_news) as total
						FROM mc_news mn
							JOIN mc_news_content mnc ON mn.id_news = mnc.id_news
							JOIN mc_lang ml ON mnc.id_lang = ml.id_lang
							'.$joins.'
						WHERE ml.iso_lang = :iso 
							AND mnc.published_news = 1
						 '.$where
						.$group
						.$order
						.$limit;
					break;
                case 'pageLang':
                    $query = 'SELECT p.*,c.*,lang.*
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_news = :id
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
				$query = 'INSERT INTO `mc_news`(date_register) VALUE (NOW())';
				$params = array();
		    	break;
		    case 'content':
				$query = 'INSERT INTO `mc_news_content`(id_news,id_lang,name_news,url_news,resume_news,content_news,seo_title_news,seo_desc_news,date_publish,published_news) 
						VALUES (:id_news,:id_lang,:name_news,:url_news,:resume_news,:content_news,:seo_title_news,:seo_desc_news,:date_publish,:published_news)';
		    	break;
		    case 'newTagComb':
				$queries = [
					['request'=>'INSERT INTO mc_news_tag (id_lang,name_tag) VALUE (:id_lang,:name_tag)','params' => ['id_lang' => $params['id_lang'],'name_tag' => $params['name_tag']]],
					['request'=>'SET @tag_id = LAST_INSERT_ID()','params' => []],
					['request'=>'SET @news_id = :id_news','params' => ['id_news' => $params['id_news']]],
					['request'=>'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUE (@news_id,@tag_id)','params' => []]
				];

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
		    	break;
		    case 'newTag':
		    	$query = 'INSERT INTO mc_news_tag (id_lang,name_tag) VALUES (:id_lang,:name_tag)';
		    	break;
		    case 'newTagRel':
		    	$query = 'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUES (:id_news,:id_tag)';
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
		    	$query = 'UPDATE mc_news_content 
						SET
							name_news = :name_news,
							url_news = :url_news,
							resume_news = :resume_news,
							content_news = :content_news,
							seo_title_news=:seo_title_news,
							seo_desc_news=:seo_desc_news,
							date_publish = :date_publish, 
							published_news = :published_news
                		WHERE id_news = :id_news AND id_lang = :id_lang';
		    	break;
		    case 'img':
		    	$query = 'UPDATE mc_news SET img_news = :img_news WHERE id_news = :id_news';
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
				$query = 'DELETE FROM `mc_news` WHERE `id_news` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'tagRel':
				$query = 'DELETE FROM `mc_news_tag_rel` WHERE `id_rel` = :id_rel';
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