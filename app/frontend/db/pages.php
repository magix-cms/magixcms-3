<?php
class frontend_db_pages
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

        if(is_array($config)) {
            if($config['context'] === 'all') {
            	switch ($config['type']) {
            	    case 'langs':
						$sql = 'SELECT
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
            	    case 'pages':
						$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
						$sql = "SELECT
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
            	    	break;
					case 'rand_pages':
						$queries = array(
							array('request'=>'CREATE TEMPORARY TABLE page_map (row_id int not NULL primary key, random_id int not null)'),
							array('request'=>'CREATE TEMPORARY TABLE random_ids (rand_id int auto_increment not NULL primary key, gen_id int not null)'),
							array('request'=>'SET @id = 0'),
							array('request'=>'
									INSERT INTO page_map 
									SELECT @id := @id + 1, p.id_pages 
									FROM mc_cms_page AS p 
									JOIN mc_cms_page_content AS pc ON ( p.id_pages = pc.id_pages )
									JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) WHERE pc.published_pages = 1 and lang.iso_lang = :iso',
								'params'=>array('iso' => $params['iso'])),
							array('request'=>'INSERT INTO random_ids (gen_id) VALUES '.$params['ids']),
							array('request'=>"
							SELECT rows.random_id
							FROM page_map as rows
							JOIN random_ids as ids ON(rows.row_id = ids.gen_id)
						",'fetch'=>true)
						);

						try {
							$result = component_routing_db::layer()->transaction($queries);
							return $result[5];
						}
						catch (Exception $e) {
							return 'Exception reçue : '.$e->getMessage();
						}
						break;
            	    case 'pages_short':
						$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
						$sql = "SELECT
									p.id_pages,
									p.id_parent,
									c.name_pages,
								   	c.url_pages,
       								COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
									lang.iso_lang
								FROM mc_cms_page AS p
								JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    			$conditions";
            	    	break;
					case 'imgs':
						$sql = 'SELECT 
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
						$sql = "SELECT 
									p.id_pages,
									p.id_parent,
									p.img_pages,
									p.menu_pages, 
									p.date_register, 
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
						$sql = "SELECT t.id_pages AS parent, GROUP_CONCAT(f.id_pages) AS children
								FROM mc_cms_page t
								JOIN mc_cms_page f ON t.id_pages=f.id_parent
								GROUP BY t.id_pages";
						break;
            	    case 'ws':
						$sql = 'SELECT
								h.*,c.*,lang.iso_lang,lang.default_lang
								FROM mc_cms_page AS h
								JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
								WHERE h.id_pages = :id';
            	    	break;
                    case 'images':
                        $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                        $sql = "SELECT img.* FROM mc_cms_page_img AS img $conditions";
                        break;
                    case 'images_content':
                        $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                        $sql = "SELECT c.*,lang.iso_lang
						FROM mc_cms_page_img_content AS c
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
                        break;
            	}

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }
            elseif($config['context'] === 'one') {
            	switch ($config['type']) {
            	    case 'page':
						$sql = 'SELECT
									h.*,
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
									lang.iso_lang
								FROM mc_cms_page AS h
								JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
								LEFT JOIN mc_cms_page_img AS img ON (h.id_pages = img.id_pages)
								LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
								WHERE h.id_pages = :id AND lang.iso_lang = :iso AND c.published_pages = 1 AND img.default_img = 1';
            	    	break;
            	    case 'root':
						$sql = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
            	    	break;
            	    case 'wsEdit':
						$sql = 'SELECT * FROM mc_cms_page WHERE `id_pages` = :id';
            	    	break;
            	    case 'image':
						$sql = 'SELECT img_pages FROM mc_cms_page WHERE `id_pages` = :id_pages';
            	    	break;
            	    case 'content':
						$sql = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
            	    	break;
                    case 'pageLang':
                        $sql = 'SELECT p.*,c.*,lang.*
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING(id_pages)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_pages = :id
							AND lang.iso_lang = :iso';
                        break;
					case 'tot_pages':
						$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
						$sql = "SELECT COUNT(DISTINCT p.id_pages) as tot
								FROM mc_cms_page AS p
								JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
								LEFT JOIN mc_cms_page_img AS img ON (p.id_pages = img.id_pages)
								LEFT JOIN mc_cms_page_img_content AS imgc ON (imgc.id_img = img.id_img and c.id_lang = imgc.id_lang)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
						break;
                    case 'lastImgId':
                        $sql = 'SELECT id_img FROM mc_cms_page_img ORDER BY id_img DESC LIMIT 0,1';
                        break;
            	}

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
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
				$cond = $params['id_parent'] != NULL ? 'IN ('.$params['id_parent'].')' : 'IS NULL';
				$sql = "INSERT INTO `mc_cms_page`(id_parent,order_pages,date_register) 
						SELECT :id_parent,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent $cond";
		    	break;
		    case 'content':
				$sql = 'INSERT INTO `mc_cms_page_content`(id_pages,id_lang,name_pages,url_pages,resume_pages,content_pages,seo_title_pages,seo_desc_pages,published_pages) 
				  		VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:resume_pages,:content_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';
		    	break;
            case 'newImg':
                $sql = 'INSERT INTO `mc_cms_page_img`(id_pages,name_img,order_img,default_img) 
						SELECT :id_pages,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_cms_page_img WHERE id_pages IN ('.$params['id_pages'].')';
                break;
            case 'newImgContent':
                $sql = 'INSERT INTO `mc_cms_page_img_content`(id_img,id_lang,alt_img,title_img,caption_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img,:caption_img)';
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
			case 'pageActiveMenu':
				$sql = 'UPDATE mc_cms_page 
						SET menu_pages = :menu_pages 
						WHERE id_pages IN ('.$params['id_pages'].')';
				$params = array(
					':menu_pages'	=> $params['menu_pages']
				);
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
				$sql = 'DELETE FROM `mc_cms_page` WHERE `id_pages` IN ('.$params['id'].')';
				$params = array();
				break;
            case 'delImages':
                $sql = 'DELETE FROM `mc_cms_page_img` WHERE `id_img` IN ('.$params['id'].')';
                $params = array();
                break;
            case 'delImagesAll':
                $sql = 'DELETE FROM `mc_cms_page_img` WHERE id_pages = :id';
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