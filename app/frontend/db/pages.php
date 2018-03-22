<?php
class frontend_db_pages
{
    /**
     * @param $config
     * @param bool $data
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'langs') {
                    $sql = 'SELECT
                    h.*,c.*,lang.iso_lang
                    FROM mc_cms_page AS h
                    JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    WHERE h.id_pages = :id AND c.published_pages = 1';
                    $params = $data;
                }
                elseif ($config['type'] === 'pages') {
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT
                    p.*,c.*,lang.iso_lang, lang.default_lang
                    FROM mc_cms_page AS p
                    JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    $conditions";

                    $params = $data;
                    //WHERE lang.iso_lang = :iso AND c.published_pages = 1
                }
                elseif($config['type'] === 'child'){
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT p.id_pages,p.id_parent,p.img_pages,p.menu_pages, p.date_register, c.*,lang.iso_lang
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        $conditions";

                    $params = $data;
                }
                elseif ($config['type'] === 'ws') {
                    $sql = 'SELECT
							h.*,c.*,lang.iso_lang,lang.default_lang
							FROM mc_cms_page AS h
							JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'page') {
                    $sql = 'SELECT
							h.*,c.*,lang.iso_lang
							FROM mc_cms_page AS h
							JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND lang.iso_lang = :iso AND c.published_pages = 1';
                    $params = $data;
                }elseif ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
                    //$params = $data;
                }elseif ($config['type'] === 'wsEdit') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_cms_page WHERE `id_pages` = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'image') {
                    //Return image
                    $sql = 'SELECT img_pages FROM mc_cms_page WHERE `id_pages` = :id_pages';
                    $params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     * @throws Exception
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
     * @throws Exception
     */
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            $sql = '';
            $params = $data;

            if ($config['type'] === 'page') {
                $sql = 'UPDATE mc_cms_page 
							SET 
								id_parent = :id_parent,
								menu_pages = :menu_pages
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
     * @throws Exception
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
?>