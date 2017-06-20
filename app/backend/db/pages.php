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
                    //Return current skin
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
            }
        }
    }
}