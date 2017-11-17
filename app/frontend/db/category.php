<?php
class frontend_db_category
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            } elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_catalog_cat ORDER BY id_cat DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_catalog_cat_content` WHERE `id_cat` = :id_cat AND `id_lang` = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'image') {
                    //Return image
                    $sql = 'SELECT img_cat FROM mc_catalog_cat WHERE `id_cat` = :id_cat';
                    $params = $data;
                }
                elseif ($config['type'] === 'page') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_catalog_cat WHERE `id_cat` = :id_cat';
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
            if ($config['type'] === 'page') {
                if($data['id_parent'] != NULL){
                    $sql = 'INSERT INTO `mc_catalog_cat`(id_parent,order_cat,date_register) 
                SELECT :id_parent,COUNT(id_cat),NOW() FROM mc_catalog_cat WHERE id_parent IN ('.$data['id_parent'].')';
                    component_routing_db::layer()->insert($sql,array(
                        ':id_parent'	        => $data['id_parent']
                    ));
                }else{
                    $sql = 'INSERT INTO `mc_catalog_cat`(id_parent,order_cat,date_register) 
                SELECT :id_parent,COUNT(id_cat),NOW() FROM mc_catalog_cat WHERE id_parent IS NULL';
                    component_routing_db::layer()->insert($sql,array(
                        ':id_parent'	        => $data['id_parent']
                    ));
                }


            }elseif ($config['type'] === 'content') {

                $sql = 'INSERT INTO `mc_catalog_cat_content`(id_cat,id_lang,name_cat,url_cat,resume_cat,content_cat,published_cat) 
				  VALUES (:id_cat,:id_lang,:name_cat,:url_cat,:resume_cat,:content_cat,:published_cat)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_lang'	        => $data['id_lang'],
                    ':id_cat'	        => $data['id_cat'],
                    ':name_cat'       => $data['name_cat'],
                    ':url_cat'        => $data['url_cat'],
                    ':resume_cat'        => $data['resume_cat'],
                    ':content_cat'    => $data['content_cat'],
                    ':published_cat'  => $data['published_cat']
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
            $sql = '';
            $params = $data;

            if ($config['type'] === 'content') {
                $sql = 'UPDATE mc_catalog_cat_content SET 
                name_cat = :name_cat, url_cat = :url_cat, resume_cat = :resume_cat, 
                content_cat=:content_cat, published_cat=:published_cat
                WHERE id_cat = :id_cat AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,$data);
            }elseif ($config['type'] === 'img') {
                $sql = 'UPDATE mc_catalog_cat SET img_cat = :img_cat
                WHERE id_cat = :id_cat';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_cat'	       => $data['id_cat'],
                        ':img_cat'       => $data['img_cat']
                    )
                );
            }elseif ($config['type'] === 'order') {
                $sql = 'UPDATE mc_catalog_cat SET order_cat = :order_cat
                WHERE id_cat = :id_cat';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_cat'	    => $data['id_cat'],
                        ':order_cat'	=> $data['order_cat']
                    )
                );
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
                $sql = 'DELETE FROM mc_catalog_cat WHERE id_cat IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }elseif($config['type'] === 'delProduct'){
                $sql = 'DELETE FROM mc_catalog WHERE id_catalog IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}