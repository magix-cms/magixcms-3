<?php
class backend_db_pages
{
	/**
	 * @param $config
	 * @param bool $data
	 * @return mixed|null
	 */
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
		$dateFormat = new component_format_date();

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'pages') {
					$sql = "SELECT p.id_pages, c.name_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
								FROM mc_cms_page AS p
									JOIN mc_cms_page_content AS c USING ( id_pages )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
									GROUP BY p.id_pages 
								ORDER BY p.order_pages";

                    if(isset($config['search'])) {
						$cond = '';
						$config['search'] = array_filter($config['search']);
                    	if(is_array($config['search']) && !empty($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if($q != '') {
									$cond .= 'AND ';
									switch ($key) {
										case 'id_pages':
										case 'published_pages':
											$cond .= 'c.'.$key.' = '.$q.' ';
											break;
										case 'name_pages':
											$cond .= "c.".$key." LIKE '%".$q."%' ";
											break;
										case 'parent_pages':
											$cond .= "ca.name_pages"." LIKE '%".$q."%' ";
											break;
										case 'menu_pages':
											$cond .= 'p.'.$key.' = '.$q.' ';
											break;
										case 'date_register':
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "p.".$key." LIKE '%".$q."%' ";
											break;
									}
									$nbc++;
								}
							}

							$sql = "SELECT p.id_pages, c.name_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register, ca.name_pages AS parent_pages
								FROM mc_cms_page AS p
									JOIN mc_cms_page_content AS c USING ( id_pages )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
									LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
									WHERE c.id_lang = :default_lang $cond
									GROUP BY p.id_pages 
								ORDER BY p.order_pages";
						}
                    }

                    $params = $data;
                }
                elseif ($config['type'] === 'pagesChild') {
                    $cond = '';
                    if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                        $nbc = 0;
                        foreach ($config['search'] as $key => $q) {
                            if($q != '') {
                                $cond .= 'AND ';
                                switch ($key) {
                                    case 'id_pages':
                                        $cond .= 'c.'.$key.' = '.$q.' ';
                                        break;
                                    case 'name_pages':
                                        $cond .= "c.".$key." LIKE '%".$q."%' ";
                                        break;
                                    case 'menu_pages':
                                        $cond .= 'p.'.$key.' = '.$q.' ';
                                        break;
                                    case 'date_register':
										$q = $dateFormat->date_to_db_format($q);
                                        $cond .= "p.".$key." LIKE '%".$q."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }

					$sql = "SELECT p.id_pages, c.name_pages, p.menu_pages, p.date_register
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        WHERE p.id_parent = :id $cond
                        GROUP BY p.id_pages 
                    ORDER BY p.order_pages";

                    $params = $data;
                }
                elseif ($config['type'] === 'pagesSelect') {
                    //List pages for select
                    $sql = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        WHERE c.id_lang = :default_lang
                        GROUP BY p.id_pages 
                    ORDER BY p.id_pages DESC";
                    $params = $data;
                }
                elseif ($config['type'] === 'pagesPublishedSelect') {
                    //List pages for select
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
                    $params = $data;
                }
                elseif ($config['type'] === 'page') {
                    $sql = 'SELECT p.*,c.*,lang.*
                        FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING(id_pages)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE p.id_pages = :edit';

                    $params = $data;
                }
                elseif ($config['type'] === 'img') {
                    $sql = 'SELECT p.id_pages, p.img_pages
                        FROM mc_cms_page AS p WHERE p.img_pages IS NOT NULL';
                }
                elseif ($config['type'] === 'sitemap') {
                    $sql = 'SELECT p.id_pages, p.img_pages, c.name_pages, c.url_pages, lang.iso_lang, c.id_lang, c.last_update
                        FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        WHERE c.published_pages = 1 AND c.id_lang = :id_lang
                        ORDER BY p.id_pages ASC';
                    $params = $data;
                }
                elseif ($config['type'] === 'lastPages') {
                    //### -- Dashboard Data
                    $sql = "SELECT p.id_pages, c.name_pages, p.date_register
							FROM mc_cms_page AS p
							JOIN mc_cms_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang
							GROUP BY p.id_pages 
							ORDER BY p.id_pages DESC
							LIMIT 5";
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;

            } elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'page') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_cms_page WHERE `id_pages` = :id_pages';
                    $params = $data;
                }
                elseif ($config['type'] === 'pageLang') {
                    //Return current row
                    $sql = 'SELECT p.*,c.*,lang.*
                        FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING(id_pages)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE p.id_pages = :id
                        AND lang.iso_lang = :iso';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     */
    public function insert($config,$data = false)
    {
        if (is_array($config)) {
			$sql = '';
			$params = $data;

            if ($config['type'] === 'page') {
            	$cond = $data['id_parent'] != NULL ? 'IN ('.$data['id_parent'].')' : 'IS NULL';
				$sql = "INSERT INTO `mc_cms_page`(id_parent,order_pages,date_register) 
						SELECT :id_parent,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent ".$cond;
            }
            elseif ($config['type'] === 'content') {
                $sql = 'INSERT INTO `mc_cms_page_content`(id_pages,id_lang,name_pages,url_pages,resume_pages,content_pages,seo_title_pages,seo_desc_pages,published_pages) 
				  		VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:resume_pages,:content_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';
            }

			if($sql && $params) component_routing_db::layer()->insert($sql,$params);
        }
    }

	/**
	 * @param $config
	 * @param bool $data
	 */
	public function update($config,$data = false)
	{
		if (is_array($config)) {
			$sql = '';
			$params = $data;

			if ($config['type'] === 'page') {
				$sql = 'UPDATE mc_cms_page 
							SET 
								id_parent = :id_parent
							WHERE id_pages = :id_pages';
			}
			elseif ($config['type'] === 'content') {
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
			}
			elseif ($config['type'] === 'img') {
				$sql = 'UPDATE mc_cms_page 
						SET img_pages = :img_pages
                		WHERE id_pages = :id_pages';
			}
			elseif ($config['type'] === 'pageActiveMenu') {
				$query = 'UPDATE mc_cms_page 
						SET menu_pages = :menu_pages 
						WHERE id_pages IN ('.$data['id_pages'].')';
                component_routing_db::layer()->update($query,
                    array(
                        ':menu_pages'	=> $data['menu_pages']
                    )
                );
			}
			elseif ($config['type'] === 'order') {
				$sql = 'UPDATE mc_cms_page 
						SET order_pages = :order_pages
                		WHERE id_pages = :id_pages';
			}

			if($sql && $params) component_routing_db::layer()->update($sql,$params);
		}
	}

    /**
     * @param $config
     * @param bool $data
     */
    public function delete($config,$data = false)
    {
        if (is_array($config)) {
            if($config['type'] === 'delPages'){
                $sql = 'DELETE FROM mc_cms_page 
						WHERE id_pages IN ('.$data['id'].')';
            }

			if($sql) component_routing_db::layer()->delete($sql,array());
        }
    }
}