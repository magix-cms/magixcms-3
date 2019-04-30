<?php
class backend_db_pages
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
				case 'pages':
					$limit = '';
					if($config['offset']) {
						$limit = ' LIMIT 0, '.$config['offset'];
						if(isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
						}
					}

					$sql = "SELECT p.id_pages, c.name_pages, p.img_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
						FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
							GROUP BY p.id_pages 
						ORDER BY p.order_pages".$limit;

					if(isset($config['search'])) {
						$cond = '';
						if(is_array($config['search']) && !empty($config['search'])) {
							$nbc = 1;
							foreach ($config['search'] as $key => $q) {
								if($q !== '') {
									$cond .= 'AND ';
									$p = 'p'.$nbc;
									switch ($key) {
										case 'id_pages':
										case 'menu_pages':
											$cond .= 'p.'.$key.' = :'.$p.' ';
											break;
										case 'published_pages':
											$cond .= 'c.'.$key.' = :'.$p.' ';
											break;
										case 'name_pages':
											$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'parent_pages':
											$cond .= "ca.name_pages"." LIKE CONCAT('%', :".$p.", '%') ";
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

							$sql = "SELECT p.id_pages, c.name_pages, p.img_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register, ca.name_pages AS parent_pages
								FROM mc_cms_page AS p
									JOIN mc_cms_page_content AS c USING ( id_pages )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
									LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
									WHERE c.id_lang = :default_lang $cond
									GROUP BY p.id_pages 
								ORDER BY p.order_pages".$limit;
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
									case 'id_pages':
										$cond .= 'c.'.$key.' = '.$p.' ';
										break;
									case 'name_pages':
										$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
									case 'menu_pages':
										$cond .= 'p.'.$key.' = '.$p.' ';
										break;
									case 'date_register':
										$q = $dateFormat->date_to_db_format($q);
										$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										//$params[$key] = $q;
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}

					$sql = "SELECT p.id_pages, c.name_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
							LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
							WHERE p.id_parent = :id $cond
							GROUP BY p.id_pages 
							ORDER BY p.order_pages";
					break;
				case 'pagesSelect':
					$sql = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
							FROM mc_cms_page AS p
								JOIN mc_cms_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
								LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
								WHERE c.id_lang = :default_lang
								GROUP BY p.id_pages 
							ORDER BY p.id_pages DESC";
					break;
				case 'pagesPublishedSelect':
					$sql = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
							FROM mc_cms_page AS p
								JOIN mc_cms_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
								LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
								WHERE c.id_lang = :default_lang
								AND c.published_pages = 1
								GROUP BY p.id_pages 
							ORDER BY p.id_pages DESC";
					break;
				case 'page':
					$sql = 'SELECT p.*,c.*,lang.*
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING(id_pages)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_pages = :edit';
					break;
				case 'img':
					$sql = 'SELECT p.id_pages, p.img_pages FROM mc_cms_page AS p WHERE p.img_pages IS NOT NULL';
					break;
				case 'sitemap':
					$sql = 'SELECT p.id_pages, p.img_pages, c.name_pages, c.url_pages, lang.iso_lang, c.id_lang, c.last_update
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.published_pages = 1 AND c.id_lang = :id_lang
							ORDER BY p.id_pages ASC';
					break;
				case 'lastPages':
					$sql = "SELECT p.id_pages, c.name_pages, p.date_register
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang
							GROUP BY p.id_pages 
							ORDER BY p.id_pages DESC
							LIMIT 5";
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$sql = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
					break;
				case 'page':
					$sql = 'SELECT * FROM mc_cms_page WHERE `id_pages` = :id_pages';
					break;
				case 'pageLang':
					$sql = 'SELECT p.*,c.*,lang.*
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING(id_pages)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_pages = :id
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
    public function insert($config,$params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'page':
				$cond = $params['id_parent'] != NULL ? ' IN ('.$params['id_parent'].')' : ' IS NULL';
				$sql = "INSERT INTO `mc_cms_page`(id_parent,menu_pages,order_pages,date_register) 
						SELECT :id_parent,:menu_pages,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent".$cond;
				break;
			case 'content':
				$sql = 'INSERT INTO `mc_cms_page_content`(id_pages,id_lang,name_pages,url_pages,resume_pages,content_pages,seo_title_pages,seo_desc_pages,published_pages) 
				  		VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:resume_pages,:content_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';
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
	public function update($config,$params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'page':
				$sql = 'UPDATE mc_cms_page 
							SET 
								id_parent = :id_parent,
							    menu_pages = :menu_pages
							WHERE id_pages = :id_pages';
				break;
			case 'content':
				$sql = 'UPDATE mc_cms_page_content 
						SET 
							name_pages = :name_pages,
							url_pages = :url_pages,
							resume_pages = :resume_pages,
							content_pages=:content_pages,
							seo_title_pages=:seo_title_pages,
							seo_desc_pages=:seo_desc_pages, 
							published_pages=:published_pages
                		WHERE id_pages = :id_pages 
                		AND id_lang = :id_lang';
				break;
			case 'img':
				$sql = 'UPDATE mc_cms_page 
						SET img_pages = :img_pages
                		WHERE id_pages = :id_pages';
				break;
			case 'imgContent':
				$sql = 'UPDATE mc_cms_page_content 
						SET 
							alt_img = :alt_img,
							title_img = :title_img,
							caption_img = :caption_img
                		WHERE id_pages = :id_pages 
                		AND id_lang = :id_lang';
				break;
			case 'pageActiveMenu':
				$sql = 'UPDATE mc_cms_page 
						SET menu_pages = :menu_pages 
						WHERE id_pages IN ('.$params['id_pages'].')';
				$params = array('menu_pages' => $params['menu_pages']);
				break;
			case 'order':
				$sql = 'UPDATE mc_cms_page 
						SET order_pages = :order_pages
                		WHERE id_pages = :id_pages';
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
				$sql = 'DELETE FROM mc_cms_page 
						WHERE id_pages IN ('.$params['id'].')';
				$params = array();
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