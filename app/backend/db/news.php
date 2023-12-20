<?php
class backend_db_news {
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
				case 'page':
					$query = "SELECT p.* , c.* , lang.* , rel.tags_news
							FROM mc_news AS p
							JOIN mc_news_content AS c USING ( id_news )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT OUTER JOIN (
								SELECT tagrel.id_news, lang.id_lang, GROUP_CONCAT( tag.name_tag ORDER BY tagrel.id_rel SEPARATOR ',' ) AS tags_news
								FROM mc_news_tag AS tag
								JOIN mc_news_tag_rel AS tagrel USING ( id_tag )
								JOIN mc_lang AS lang ON ( tag.id_lang = lang.id_lang )
								GROUP BY tagrel.id_news, lang.id_lang
								)rel ON ( rel.id_news = p.id_news AND rel.id_lang = c.id_lang)
							WHERE p.id_news = :edit
							ORDER BY p.id_news DESC";
					break;
				case 'pagesPublishedSelect':
					$query = "SELECT p.id_news, c.name_news
							FROM mc_news AS p
							JOIN mc_news_content AS c USING ( id_news )
							WHERE c.id_lang = :lang
							AND c.published_news = 1
							ORDER BY p.id_news DESC";
					break;
				case 'news':
					$cond = '';
					$limit = '';
					if($config['offset']) {
						$limit = ' LIMIT 0, '.$config['offset'];
						if(isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
						}
					}
					if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if($q !== '') {
								$cond .= 'AND ';
								$p = 'p'.$nbc;
								switch ($key) {
									case 'id_news':
									case 'published_news':
										$cond .= 'c.'.$key.' = :'.$p.' ';
										break;
									case 'name_news':
										$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
									case 'last_update':
									case 'date_publish':
										$q = $dateFormat->date_to_db_format($q);
										$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}
					$query = "SELECT 
								c.id_news,
								c.name_news,
								c.content_news,
								IFNULL(pi.default_img,0) as default_img,
								c.link_label_news,
								c.link_title_news,
								c.seo_title_news, 
								c.seo_desc_news,
								c.last_update,
								p.date_publish,
								c.published_news
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
                            LEFT JOIN mc_news_img AS pi ON ( p.id_news = pi.id_news AND pi.default_img = 1 )
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang $cond
							ORDER BY id_news DESC".$limit;
					break;
				/*case 'img':
					$query = 'SELECT p.id_news, p.img_news FROM mc_news AS p WHERE p.img_news IS NOT NULL';
					break;*/
				case 'tags':
					$query = 'SELECT tag.id_tag,tag.name_tag
							FROM mc_news_tag AS tag
							JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
							WHERE tag.id_lang = :id_lang';
					break;
				case 'tags_rel':
					$query = 'SELECT tag.id_tag
							FROM mc_news_tag AS tag
							LEFT JOIN mc_news_tag_rel AS tr ON(tag.id_tag = tr.id_tag)
							JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
							WHERE tr.id_news = :id';
					break;
				case 'sitemap':
					$query = "SELECT p.id_news,c.name_news,c.url_news,c.last_update,p.date_publish,c.published_news,lang.iso_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE c.published_news = 1 AND c.id_lang = :id_lang";
					break;
				case 'lastNews':
					$query = 'SELECT p.id_news,c.name_news,c.last_update,p.date_publish,c.published_news, p.date_register
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang
							ORDER BY p.id_news DESC
							LIMIT 5';
					break;
                case 'images':
                    $query = 'SELECT img.*
						FROM mc_news_img AS img
						WHERE img.id_news = :id ORDER BY order_img';
                    break;
                case 'imgData':
                    $query = 'SELECT img.id_img,img.id_news, img.name_img,c.id_lang,c.alt_img,c.title_img,c.caption_img,lang.iso_lang
							FROM mc_news_img AS img
							LEFT JOIN mc_news_img_content AS c USING(id_img)
							LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE img.id_img = :edit';
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
					$query = 'SELECT * FROM mc_news ORDER BY id_news DESC LIMIT 0,1';
					break;
				case 'page':
					$query = 'SELECT * FROM mc_news WHERE `id_news` = :id_news';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_news_content` WHERE `id_news` = :id_news AND `id_lang` = :id_lang';
					break;
				case 'tag':
					$query = 'SELECT tag.*, (SELECT id_rel FROM mc_news_tag_rel WHERE id_news = :id_news AND id_tag = tag.id_tag) AS rel_tag
							FROM mc_news_tag AS tag
							WHERE tag.id_lang = :id_lang AND tag.name_tag LIKE :name_tag';
					break;
				case 'countTags':
					$query = 'SELECT count(id_tag) AS tags FROM mc_news_tag_rel WHERE id_tag = :id_tag';
					break;
				case 'pageLang':
					$query = 'SELECT p.*,c.*,lang.*
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_news = :id
							AND lang.iso_lang = :iso';
					break;
                case 'lastImgId':
                    $query = 'SELECT id_img as `index` FROM mc_news_img WHERE id_news = :id_news ORDER BY id_img DESC LIMIT 0,1';
                    break;
                case 'imgDefault':
                    $query = 'SELECT id_img FROM mc_news_img WHERE id_news = :id AND default_img = 1';
                    break;
                case 'img':
                    $query = 'SELECT * FROM mc_news_img WHERE `id_img` = :id';
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
			case 'newTagComb':
				$queries = array(
					array('request'=>'INSERT INTO mc_news_tag (id_lang,name_tag) VALUE (:id_lang,:name_tag)','params'=>array('id_lang' => $params['id_lang'],'name_tag' => $params['name_tag'])),
					array('request'=>'SET @tag_id = LAST_INSERT_ID()','params'=>array()),
					array('request'=>'SET @news_id = :id_news','params'=>array('id_news'=>$params['id_news'])),
					array('request'=>'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUE (@news_id,@tag_id)','params'=>array())
				);

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
                catch (Exception $e) {
                    if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                    $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
                }
                break;
			case 'page':
				$query = 'INSERT INTO `mc_news`(date_publish,date_event_start,date_event_end,date_register) 
                            VALUE (:date_publish,:date_event_start,:date_event_end,NOW())';
				break;
			case 'content':
				$query = 'INSERT INTO `mc_news_content`(id_news,id_lang,name_news,longname_news,url_news,resume_news,content_news,link_label_news,link_title_news,seo_title_news, seo_desc_news,published_news) 
				  		VALUES (:id_news,:id_lang,:name_news,:longname_news,:url_news,:resume_news,:content_news,:link_label_news,:link_title_news,:seo_title_news, :seo_desc_news,:published_news)';
				break;
			case 'newTag':
				$query = 'INSERT INTO mc_news_tag (id_lang,name_tag) VALUES (:id_lang,:name_tag)';
				break;
			case 'newTagRel':
				$query = 'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUES (:id_news,:id_tag)';
				break;
            case 'newImg':
                $query = 'INSERT INTO `mc_news_img`(id_news,name_img,order_img,default_img) 
						SELECT :id_news,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_news_img WHERE id_news IN ('.$params['id_news'].')';
                break;
            case 'newImgContent':
                $query = 'INSERT INTO `mc_news_img_content`(id_img,id_lang,alt_img,title_img,caption_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img,:caption_img)';
                break;
            case 'countImages':
                $query = 'SELECT count(id_img) as tot FROM mc_news_img WHERE id_news = :id';
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
                $query = 'UPDATE mc_news 
                SET 
                    date_publish = :date_publish,
                    date_event_start = :date_event_start,
                    date_event_end = :date_event_end
                WHERE id_news = :id_news';
                break;
			case 'content':
				$query = 'UPDATE mc_news_content 
						SET 
							name_news = :name_news,
							longname_news = :longname_news,
							url_news = :url_news,
							resume_news = :resume_news,
							content_news = :content_news,
							link_label_news = :link_label_news,
							link_title_news = :link_title_news,
							seo_title_news = :seo_title_news,
							seo_desc_news = :seo_desc_news,
							published_news = :published_news
						WHERE id_news = :id_news AND id_lang = :id_lang';
				break;
			/*case 'img':
				$query = 'UPDATE mc_news SET img_news = :img_news WHERE id_news = :id_news';
				break;*/
            case 'img':
                $query = 'UPDATE mc_news_img 
						SET 
							name_img = :name_img
                		WHERE id_img = :id_img';
                break;
			case 'imgContent':
                $query = 'UPDATE mc_news_img_content 
						SET 
							alt_img = :alt_img, 
							title_img = :title_img,
							caption_img = :caption_img
                		WHERE id_img = :id_img AND id_lang = :id_lang';
				break;
            case 'imageDefault':
                $query = 'UPDATE mc_news_img
                		SET default_img = CASE id_img
							WHEN :id_img THEN 1
							ELSE 0
						END
						WHERE id_news = :id';
                break;
            case 'firstImageDefault':
                $query = 'UPDATE mc_news_img
                		SET default_img = 1
                		WHERE id_news = :id 
						ORDER BY order_img 
						LIMIT 1';
                break;
            case 'order':
                $query = 'UPDATE mc_news_img SET order_img = :order_img
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
				$query = 'DELETE FROM `mc_news` WHERE `id_news` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'tagRel':
				$query = 'DELETE FROM mc_news_tag_rel WHERE id_rel = :id_rel';
				break;
			case 'tags':
				$query = 'DELETE FROM mc_news_tag WHERE id_tag = :id_tag';
				break;
            case 'delImages':
                $query = 'DELETE FROM `mc_news_img` WHERE `id_img` IN ('.$params['id'].')';
                $params = [];
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