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
								c.date_publish,
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
								c.date_publish,
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
							$conditions";
			    	break;
			    case 'pages_short':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT 
								p.id_news,
								c.name_news,
								c.url_news,
								c.date_publish,
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
			    case 'tags':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT tag.id_tag,tag.name_tag,lang.iso_lang
						FROM mc_news_tag AS tag
						JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang) 
						$conditions";
			    	break;
			    case 'archives':
					$query = "SELECT GROUP_CONCAT(DISTINCT MONTH(`date_publish`)) AS mths, YEAR(`date_publish`) AS yr
						FROM mc_news AS news
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang USING(id_lang)
						WHERE c.published_news = 1
						AND lang.iso_lang = :iso
						GROUP BY YEAR(date_publish)
						ORDER BY date_publish DESC";
			    	break;
			    case 'ws':
					$query = 'SELECT p.img_news,c.*,lang.iso_lang,lang.default_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
						WHERE p.id_news = :id';
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
								p.id_news,
								p.img_news,
								c.name_news,
								c.url_news,
								c.resume_news,
								c.content_news,
								c.published_news,
								p.date_register,
								c.date_publish,
								COALESCE(c.alt_img, c.name_news) as alt_img,
								COALESCE(c.title_img, c.alt_img, c.name_news) as title_img,
								COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_news) as caption_img,
								c.seo_title_news,
								c.seo_desc_news,
								lang.iso_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
							WHERE p.id_news = :id AND lang.iso_lang = :iso AND c.published_news = 1';
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
								c.date_publish,
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
								c.date_publish,
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
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$query = "SELECT COUNT(p.id_news) as nbp
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						$conditions";
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