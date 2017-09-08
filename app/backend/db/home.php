<?php
class backend_db_home{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'pages') {
                    $sql = 'SELECT h.*,c.*
                    FROM mc_home_page AS h
                    JOIN mc_home_page_content AS c USING(id_page)
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)';
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    //Return current skin
                    $sql = 'SELECT * FROM mc_home_page ORDER BY id_page DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_home_page_content` WHERE `id_page` = :id_page AND `id_lang` = :id_lang';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
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
            if ($config['type'] === 'newHome') {

                $sql = 'INSERT INTO `mc_home_page`(`date_register`) VALUES (NOW())';
                component_routing_db::layer()->insert($sql,array());

            }elseif ($config['type'] === 'newContent') {

                $sql = 'INSERT INTO `mc_home_page_content`(id_page,id_lang,title_page,content_page,seo_title_page,seo_desc_page,published) 
				  VALUES (:id_page,:id_lang,:title_page,:content_page,:seo_title_page,:seo_desc_page,:published)';
                component_routing_db::layer()->insert($sql,array(
                    ':title_page'       => $data['title_page'],
                    ':content_page'     => $data['content_page'],
                    ':seo_title_page'   => $data['seo_title_page'],
                    ':seo_desc_page'    => $data['seo_desc_page'],
                    ':published'        => $data['published'],
                    ':id_page'          => $data['id_page'],
                    ':id_lang'          => $data['id_lang']
                ));
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
                $sql = 'UPDATE mc_home_page_content SET title_page = :title_page, content_page=:content_page, seo_title_page=:seo_title_page, seo_desc_page=:seo_desc_page, published=:published
                WHERE id_page = :id_page AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	        => $data['id_lang'],
                        ':id_page'	        => $data['id_page'],
                        ':title_page'       => $data['title_page'],
                        ':content_page'     => $data['content_page'],
                        ':seo_title_page'   => $data['seo_title_page'],
                        ':seo_desc_page'    => $data['seo_desc_page'],
                        ':published'        => $data['published']
                    )
                );
            }
        }
    }
}
?>