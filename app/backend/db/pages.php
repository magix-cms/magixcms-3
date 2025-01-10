<?php
class backend_db_pages {
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
					$limit = '';
					if($config['offset']) {
						$limit = ' LIMIT 0, '.$config['offset'];
						if(isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
						}
					}

					$query = "SELECT p.id_pages, c.name_pages, IFNULL(pi.default_img,0) as default_img, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
						FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
						    LEFT JOIN mc_cms_page_img AS pi ON ( p.id_pages = pi.id_pages AND pi.default_img = 1 )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
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

							$query = "SELECT p.id_pages, c.name_pages, IFNULL(pi.default_img,0) as img_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, 
                                    p.date_register, ca.name_pages AS parent_pages
								FROM mc_cms_page AS p
									JOIN mc_cms_page_content AS c USING ( id_pages )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
									LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
								    LEFT JOIN mc_cms_page_img AS pi ON ( p.id_pages = pi.id_pages AND pi.default_img = 1 )
									WHERE c.id_lang = :default_lang $cond
								ORDER BY p.id_pages".$limit;
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
								$p = 'mcp'.$nbc;
								switch ($key) {
									case 'id_pages':
										$cond .= 'mcpc.'.$key.' = '.$p.' ';
										break;
									case 'name_pages':
										$cond .= "mcpc.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
									case 'menu_pages':
										$cond .= 'mcp.'.$key.' = '.$p.' ';
										break;
									case 'date_register':
										$q = $dateFormat->date_to_db_format($q);
										$cond .= "mcp.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										//$params[$key] = $q;
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}

					/*$query = "SELECT p.id_pages, c.name_pages, c.resume_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register, IFNULL(pi.default_img,0) as default_img
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
							LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages )
                            LEFT JOIN mc_cms_page_img AS pi ON ( p.id_pages = pi.id_pages AND pi.default_img = 1 )
							WHERE p.id_parent = :id AND c.id_lang = :default_lang $cond";*/
					$query = "SELECT 
								mcp.id_pages, 
								mcpc.name_pages, 
								mcpc.resume_pages,
								mcpc.content_pages, 
								mcpc.seo_title_pages, 
								mcpc.seo_desc_pages, 
								mcp.menu_pages, 
								mcp.date_register, 
								IFNULL(mcpi.default_img,0) as default_img
							FROM mc_cms_page mcp
							JOIN mc_cms_page_content mcpc USING ( id_pages )
							JOIN mc_lang ml ON ( mcpc.id_lang = ml.id_lang )
                            LEFT JOIN mc_cms_page_img mcpi ON ( mcp.id_pages = mcpi.id_pages AND mcpi.default_img = 1 )
							WHERE mcp.id_parent = :id AND mcpc.id_lang = :default_lang $cond ORDER BY mcp.order_pages ASC";
					break;
				case 'pagesSelect':
					$query = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
							FROM mc_cms_page AS p
								JOIN mc_cms_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
								LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
								WHERE c.id_lang = :default_lang
							ORDER BY p.id_pages DESC";
					break;
				case 'pagesPublishedSelect':
					$query = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
							FROM mc_cms_page AS p
								JOIN mc_cms_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
								LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages AND c.id_lang = ca.id_lang ) 
								WHERE c.id_lang = :default_lang
								AND c.published_pages = 1
							ORDER BY p.id_pages DESC";
					break;
				case 'page':
					$query = 'SELECT p.*,c.*,lang.*
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING(id_pages)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_pages = :edit';
					break;
				/*case 'img':
					$query = 'SELECT p.id_pages, p.img_pages FROM mc_cms_page AS p WHERE p.img_pages IS NOT NULL';
					break;*/
                case 'images':
                    $query = 'SELECT img.*
						FROM mc_cms_page_img AS img
						WHERE img.id_pages = :id ORDER BY order_img';
                    break;
                case 'imagesAll':
                    $query = 'SELECT img.* FROM mc_cms_page_img AS img';
                    break;
                case 'imgData':
                    $query = 'SELECT img.id_img,img.id_pages, img.name_img,c.id_lang,c.alt_img,c.title_img,c.caption_img,lang.iso_lang
							FROM mc_cms_page_img AS img
							LEFT JOIN mc_cms_page_img_content AS c USING(id_img)
							LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE img.id_img = :edit';
                    break;
				case 'sitemap':
					$query = 'SELECT p.id_pages, IFNULL(pi.default_img,0) as img_pages, c.name_pages, c.url_pages, lang.iso_lang, c.id_lang, c.last_update
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							LEFT JOIN mc_cms_page_img AS pi ON ( p.id_pages = pi.id_pages AND pi.default_img = 1 )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.published_pages = 1 AND c.id_lang = :id_lang
							ORDER BY p.id_pages';
					break;
				case 'lastPages':
					$query = "SELECT p.id_pages, c.name_pages, p.date_register
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang
							ORDER BY p.id_pages DESC
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
					$query = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
					break;
				case 'page':
					$query = 'SELECT * FROM mc_cms_page WHERE `id_pages` = :id_pages';
					break;
				case 'pageLang':
					$query = 'SELECT p.*,c.*,lang.*
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING(id_pages)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_pages = :id
							AND lang.iso_lang = :iso';
					break;
                case 'imgContent':
                    $query = 'SELECT * FROM mc_cms_page_img_content WHERE `id_img` = :id_img AND `id_lang` = :id_lang';
                    break;
                case 'img':
                    $query = 'SELECT * FROM mc_cms_page_img WHERE `id_img` = :id';
                    break;
                case 'lastImgId':
                    $query = 'SELECT id_img as `index` FROM mc_cms_page_img WHERE id_pages = :id_pages ORDER BY id_img DESC LIMIT 0,1';
                    break;
                case 'imgDefault':
                    $query = 'SELECT id_img FROM mc_cms_page_img WHERE id_pages = :id AND default_img = 1';
                    break;
				case 'countImages':
					$query = 'SELECT count(id_img) as tot FROM mc_cms_page_img WHERE id_pages = :id';
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
				$cond = $params['id_parent'] != NULL ? ' IN ('.$params['id_parent'].')' : ' IS NULL';
				$query = "INSERT INTO `mc_cms_page`(id_parent,menu_pages,order_pages,date_register) 
						SELECT :id_parent,:menu_pages,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent".$cond;
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
            case 'imgContent':
                $query = 'UPDATE mc_cms_page_img_content 
						SET 
							alt_img = :alt_img, 
							title_img = :title_img,
							caption_img = :caption_img
                		WHERE id_img = :id_img AND id_lang = :id_lang';
                break;
            case 'img':
                $query = 'UPDATE mc_cms_page_img 
						SET 
							name_img = :name_img
                		WHERE id_img = :id_img';
                break;
			case 'pageActiveMenu':
				$query = 'UPDATE mc_cms_page 
						SET menu_pages = :menu_pages 
						WHERE id_pages IN ('.$params['id_pages'].')';
				$params = array('menu_pages' => $params['menu_pages']);
				break;
			case 'order':
				$query = 'UPDATE mc_cms_page 
						SET order_pages = :order_pages
                		WHERE id_pages = :id_pages';
				break;
            case 'order_img':
                $query = 'UPDATE mc_cms_page_img SET order_img = :order_img
                		WHERE id_img = :id_img';
                break;
            case 'imageDefault':
                $query = 'UPDATE mc_cms_page_img
                		SET default_img = CASE id_img
							WHEN :id_img THEN 1
							ELSE 0
						END
						WHERE id_pages = :id';
                break;
            case 'firstImageDefault':
                $query = 'UPDATE mc_cms_page_img
                		SET default_img = 1
                		WHERE id_pages = :id 
						ORDER BY order_img 
						LIMIT 1';
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
				$query = 'DELETE FROM mc_cms_page 
						WHERE id_pages IN ('.$params['id'].')';
				$params = array();
				break;
            case 'delImages':
                $query = 'DELETE FROM `mc_cms_page_img` WHERE `id_img` IN ('.$params['id'].')';
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
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}