<?php
class backend_db_pages
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'pages') {
                    $cond = '';
                    if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                        $nbc = 0;
                        foreach ($config['search'] as $key => $q) {
                            if($q != '') {
                                /*if($nbc > 0) {
                                    $cond .= 'AND ';
                                } else {
                                    $cond = 'WHERE ';
                                }*/
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
                                        $cond .= "p.".$key." LIKE '%".$q."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                        $sql = "SELECT p.id_parent, p.menu_pages, p.order_pages, p.date_register, c.* , ca.name_pages AS parent_pages
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        WHERE c.id_lang = :default_lang $cond
                        GROUP BY p.id_pages 
                    ORDER BY p.order_pages";
                    }else{
                        $sql = "SELECT p.id_parent, p.menu_pages, p.order_pages, p.date_register, c.*
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
                        GROUP BY p.id_pages 
                    ORDER BY p.order_pages";
                    }


                    $params = $data;

                }elseif ($config['type'] === 'pagesChild') {
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
                                        $cond .= "p.".$key." LIKE '%".$q."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT p.id_parent, p.menu_pages, p.order_pages, p.date_register, c.*
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        WHERE p.id_parent = :edit $cond
                        GROUP BY p.id_pages 
                    ORDER BY p.order_pages";


                    $params = $data;

                }elseif ($config['type'] === 'pagesSelect') {
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

                }elseif ($config['type'] === 'page') {
                    $sql = 'SELECT p.*,c.*,lang.*
                        FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING(id_pages)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE p.id_pages = :edit';

                    $params = $data;

                }
                //print $sql;
                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;

            } elseif ($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_cms_page ORDER BY id_pages DESC LIMIT 0,1';
                    //$params = $data;
                } elseif ($config['type'] === 'content') {

                    $sql = 'SELECT * FROM `mc_cms_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
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
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'content') {
                $sql = 'UPDATE mc_cms_page_content SET name_pages = :name_pages, url_pages = :url_pages, content_pages=:content_pages, seo_title_pages=:seo_title_pages, seo_desc_pages=:seo_desc_pages, 
                published_pages=:published_pages
                WHERE id_pages = :id_pages AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	        => $data['id_lang'],
                        ':id_pages'	        => $data['id_pages'],
                        ':name_pages'       => $data['name_pages'],
                        ':url_pages'        => $data['url_pages'],
                        ':content_pages'    => $data['content_pages'],
                        ':seo_title_pages'  => $data['seo_title_pages'],
                        ':seo_desc_pages'   => $data['seo_desc_pages'],
                        ':published_pages'  => $data['published_pages']
                    )
                );
            }elseif ($config['type'] === 'pageActiveMenu') {
                $sql = 'UPDATE mc_cms_page SET menu_pages = :menu_pages WHERE id_pages IN ('.$data['id_pages'].')';
                component_routing_db::layer()->update($sql,
                    array(
                        ':menu_pages' => $data['menu_pages']
                    )
                );
            }elseif ($config['type'] === 'order') {
                $sql = 'UPDATE mc_cms_page SET order_pages = :order_pages
                WHERE id_pages = :id_pages';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_pages'	    => $data['id_pages'],
                        ':order_pages'	=> $data['order_pages']
                    )
                );
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
            if ($config['type'] === 'newPages') {
                if($data['id_parent'] != NULL){
                    $sql = 'INSERT INTO `mc_cms_page`(id_parent,order_pages,date_register) 
                SELECT :id_parent,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent IN ('.$data['id_parent'].')';
                    component_routing_db::layer()->insert($sql,array(
                        ':id_parent'	        => $data['id_parent']
                    ));
                }else{
                    $sql = 'INSERT INTO `mc_cms_page`(id_parent,order_pages,date_register) 
                SELECT :id_parent,COUNT(id_pages),NOW() FROM mc_cms_page WHERE id_parent IS NULL';
                    component_routing_db::layer()->insert($sql,array(
                        ':id_parent'	        => $data['id_parent']
                    ));
                }


            }elseif ($config['type'] === 'newContent') {

                $sql = 'INSERT INTO `mc_cms_page_content`(id_pages,id_lang,name_pages,url_pages,content_pages,seo_title_pages,seo_desc_pages,published_pages) 
				  VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:content_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_lang'	        => $data['id_lang'],
                    ':id_pages'	        => $data['id_pages'],
                    ':name_pages'       => $data['name_pages'],
                    ':url_pages'        => $data['url_pages'],
                    ':content_pages'    => $data['content_pages'],
                    ':seo_title_pages'  => $data['seo_title_pages'],
                    ':seo_desc_pages'   => $data['seo_desc_pages'],
                    ':published_pages'  => $data['published_pages']
                ));
            }
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
                $sql = 'DELETE FROM mc_cms_page WHERE id_pages IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}