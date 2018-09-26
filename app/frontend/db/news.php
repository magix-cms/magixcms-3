<?php
class frontend_db_news
{
	/**
	* @param $config
	* @param bool $params
	* @return mixed|null
	* @throws Exception
	*/
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if($config['context'] === 'all') {
			switch ($config['type']) {
			    case 'langs':
					$sql = 'SELECT p.*,c.*,lang.iso_lang,lang.default_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE p.id_news = :id AND c.published_news = 1';
			    	break;
			    case 'pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$sql = "SELECT p.*,c.*,lang.iso_lang,lang.default_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						$conditions";
			    	break;
			    case 'tagsRel':
					$sql = 'SELECT tag.id_tag,tag.name_tag,lang.iso_lang
						FROM mc_news_tag AS tag
						JOIN mc_news_tag_rel AS tagrel USING ( id_tag )
						JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
						WHERE tagrel.id_news = :id AND lang.iso_lang = :iso';
			    	break;
			    case 'tags':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT tag.id_tag,tag.name_tag,lang.iso_lang
						FROM mc_news_tag AS tag
						JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang) 
						$conditions";
			    	break;
			    case 'archives':
					$sql = "SELECT GROUP_CONCAT(DISTINCT MONTH(`date_publish`)) AS mths, YEAR(`date_publish`) AS yr
						FROM mc_news AS news
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang USING(id_lang)
						WHERE c.published_news = 1
						AND lang.iso_lang = :iso
						GROUP BY YEAR(date_publish)
						ORDER BY date_publish DESC";
			    	break;
			    case 'ws':
					$sql = 'SELECT p.img_news,c.*,lang.iso_lang,lang.default_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
						WHERE p.id_news = :id';
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
			    case 'page':
					$sql = 'SELECT p.img_news,c.*,lang.iso_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
						WHERE p.id_news = :id AND lang.iso_lang = :iso AND c.published_news = 1';
			    	break;
			    case 'nb_archives':
					$sql = "SELECT COUNT(`id_news`) AS nbr
						FROM mc_news AS news
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang USING(id_lang)
						WHERE c.published_news = 1
						AND lang.iso_lang = :iso
						AND YEAR(date_publish) = :yr
						AND MONTH(date_publish) = :mth";
			    	break;
			    case 'tag':
					$sql = "SELECT id_tag as id, name_tag as name FROM mc_news_tag WHERE id_tag = :id";
			    	break;
				// Web Service
			    case 'root':
					$sql = 'SELECT * FROM mc_news ORDER BY id_news DESC LIMIT 0,1';
			    	break;
			    case 'wsEdit':
					$sql = 'SELECT * FROM mc_news WHERE `id_news` = :id';
			    	break;
			    case 'image':
					$sql = 'SELECT img_news FROM mc_news WHERE `id_news` = :id_news';
			    	break;
			    case 'content':
					$sql = 'SELECT * FROM `mc_news_content` WHERE `id_news` = :id_news AND `id_lang` = :id_lang';
			    	break;
			    case 'tag_ws':
					$sql = 'SELECT tag.*, (SELECT id_rel FROM mc_news_tag_rel WHERE id_news = :id_news AND id_tag = tag.id_tag) AS rel_tag
							FROM mc_news_tag AS tag
							WHERE tag.id_lang = :id_lang AND tag.name_tag LIKE :name_tag';
			    	break;
			    case 'prev_page':
					$sql = "SELECT c.*,lang.iso_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
							WHERE lang.iso_lang = :iso AND c.published_news = 1
							AND (c.date_publish < :date_publish OR (c.date_publish = '".$params['date_publish']."' AND p.id_news < :id))
							ORDER BY c.date_publish DESC LIMIT 1";
			    	break;
			    case 'next_page':
					$sql = "SELECT c.*,lang.iso_lang
							FROM mc_news AS p
							JOIN mc_news_content AS c ON(c.id_news = p.id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
							WHERE lang.iso_lang = :iso AND c.published_news = 1
							AND (c.date_publish > :date_publish OR (c.date_publish = '".$params['date_publish']."' AND p.id_news > :id))
							ORDER BY c.date_publish ASC LIMIT 1";
			    	break;
				case 'count_news':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$sql = "SELECT COUNT(p.id_news) as nbp
						FROM mc_news AS p
						JOIN mc_news_content AS c ON(c.id_news = p.id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						$conditions";
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
		    case 'page':
				$sql = 'INSERT INTO `mc_news`(date_register) VALUE (NOW())';
				$params = array();
		    	break;
		    case 'content':
				$sql = 'INSERT INTO `mc_news_content`(id_news,id_lang,name_news,url_news,resume_news,content_news,date_publish,published_news) 
						VALUES (:id_news,:id_lang,:name_news,:url_news,:resume_news,:content_news,:date_publish,:published_news)';
		    	break;
		    case 'newTagComb':
				$queries = array(
					array('request'=>'INSERT INTO mc_news_tag (id_lang,name_tag) VALUE (:id_lang,:name_tag)','params'=>array(':id_lang' => $params['id_lang'],':name_tag' => $params['name_tag'])),
					array('request'=>'SET @tag_id = LAST_INSERT_ID()','params'=>array()),
					array('request'=>'SET @news_id = :id_news','params'=>array(':id_news'=>$params['id_news'])),
					array('request'=>'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUE (@news_id,@tag_id)','params'=>array())
				);

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
		    	break;
		    case 'newTag':
		    	$sql = 'INSERT INTO mc_news_tag (id_lang,name_tag) VALUES (:id_lang,:name_tag)';
		    	break;
		    case 'newTagRel':
		    	$sql = 'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUES (:id_news,:id_tag)';
		    	break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
		    case 'content':
		    	$sql = 'UPDATE mc_news_content 
						SET
							name_news = :name_news,
							url_news = :url_news,
							resume_news = :resume_news,
							content_news = :content_news,
							date_publish = :date_publish, 
							published_news = :published_news
                		WHERE id_news = :id_news AND id_lang = :id_lang';
		    	break;
		    case 'img':
		    	$sql = 'UPDATE mc_news SET img_news = :img_news WHERE id_news = :id_news';
		    	break;
		    case 'order':
		    	$sql = 'UPDATE mc_news SET order_news = :order_news WHERE id_news = :id_news';
		    	break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';
		$sql = '';

		switch ($config['type']) {
			case 'delPages':
				$sql = 'DELETE FROM `mc_news` WHERE `id_news` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'tagRel':
				$sql = 'DELETE FROM `mc_news_tag_rel` WHERE `id_rel` = :id_rel';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}