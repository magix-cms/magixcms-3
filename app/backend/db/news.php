<?php
class backend_db_news
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
		$dateFormat = new component_format_date();

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'page':
					$sql = "SELECT p.* , c.* , lang.* , rel.tags_news
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
							WHERE p.id_news = :edit";
					break;
				case 'pagesPublishedSelect':
					$sql = "SELECT p.id_news, c.name_news
							FROM mc_news AS p
							JOIN mc_news_content AS c USING ( id_news )
							WHERE c.id_lang = :lang
							AND c.published_news = 1
							ORDER BY p.id_news DESC";
					break;
				case 'news':
					$cond = '';
					if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if($q != '') {
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
					$sql = "SELECT c.id_news,c.name_news,c.content_news,p.img_news,c.last_update,c.date_publish,c.published_news
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang $cond";
					break;
				case 'img':
					$sql = 'SELECT p.id_news, p.img_news FROM mc_news AS p WHERE p.img_news IS NOT NULL';
					break;
				case 'tags':
					$sql = 'SELECT tag.id_tag,tag.name_tag
							FROM mc_news_tag AS tag
							JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
							WHERE tag.id_lang = :id_lang';
					break;
				case 'tags_rel':
					$sql = 'SELECT tag.id_tag
							FROM mc_news_tag AS tag
							LEFT JOIN mc_news_tag_rel AS tr ON(tag.id_tag = tr.id_tag)
							JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
							WHERE tr.id_news = :id';
					break;
				case 'sitemap':
					$sql = "SELECT p.id_news,p.img_news,c.name_news,c.url_news,c.last_update,c.date_publish,c.published_news,lang.iso_lang
						FROM mc_news AS p
						JOIN mc_news_content AS c USING(id_news)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE c.published_news = 1 AND c.id_lang = :id_lang";
					break;
				case 'lastNews':
					$sql = 'SELECT p.id_news,c.name_news,c.last_update,c.date_publish,c.published_news, p.date_register
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang
							ORDER BY p.id_news DESC
							LIMIT 5';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$sql = 'SELECT * FROM mc_news ORDER BY id_news DESC LIMIT 0,1';
					break;
				case 'page':
					$sql = 'SELECT * FROM mc_news WHERE `id_news` = :id_news';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_news_content` WHERE `id_news` = :id_news AND `id_lang` = :id_lang';
					break;
				case 'tag':
					$sql = 'SELECT tag.*, (SELECT id_rel FROM mc_news_tag_rel WHERE id_news = :id_news AND id_tag = tag.id_tag) AS rel_tag
							FROM mc_news_tag AS tag
							WHERE tag.id_lang = :id_lang AND tag.name_tag LIKE :name_tag';
					break;
				case 'countTags':
					$sql = 'SELECT count(id_tag) AS tags FROM mc_news_tag_rel WHERE id_tag = :id_tag';
					break;
				case 'pageLang':
					$sql = 'SELECT p.*,c.*,lang.*
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_news = :id
							AND lang.iso_lang = :iso';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
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
					return 'Exception reçue : '.$e->getMessage();
				}
				break;
			case 'newPages':
				$sql = 'INSERT INTO `mc_news`(date_register) VALUE (NOW())';
				break;
			case 'newContent':
				$sql = 'INSERT INTO `mc_news_content`(id_news,id_lang,name_news,url_news,resume_news,content_news,date_publish,published_news) 
				  		VALUES (:id_news,:id_lang,:name_news,:url_news,:resume_news,:content_news,:date_publish,:published_news)';
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
				$sql = 'DELETE FROM mc_news_tag_rel WHERE id_rel = :id_rel';
				break;
			case 'tags':
				$sql = 'DELETE FROM mc_news_tag WHERE id_tag = :id_tag';
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