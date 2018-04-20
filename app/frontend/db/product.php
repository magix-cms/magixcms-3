<?php
class frontend_db_product
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();
        if (is_array($config)) {
            if ($config['context'] === 'all') {

            }elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    $sql = 'SELECT id_product FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
                }
                elseif ($config['type'] === 'page') {
                    $sql = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'category') {
                    $sql = 'SELECT * FROM mc_catalog WHERE `id_product` = :id AND id_cat = :id_cat';
                    $params = $data;
                }
                elseif ($config['type'] === 'img') {
                    $sql = 'SELECT * FROM mc_catalog_product_img WHERE `name_img` = :name_img';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
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
            if ($config['type'] === 'newPages') {

                $sql = 'INSERT INTO `mc_catalog_product`(price_p,reference_p,date_register) 
                VALUES (:price_p,:reference_p,NOW())';

            }
            elseif ($config['type'] === 'newContent') {
                $sql = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,url_p,resume_p,content_p,published_p) 
				  VALUES (:id_product,:id_lang,:name_p,:url_p,:resume_p,:content_p,:published_p)';
            }
            elseif ($config['type'] === 'catRel') {
                $sql = 'INSERT INTO `mc_catalog` (id_product,id_cat,default_c,order_p)
						SELECT :id,:id_cat,:default_c,COUNT(id_catalog) FROM mc_catalog WHERE id_cat IN ('.$params['id_cat'].')';

                //component_routing_db::layer()->insert($sql,$data);
            }
            elseif ($config['type'] === 'newImg') {
                $sql = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img,order_img,default_img) 
						SELECT :id_product,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_catalog_product_img WHERE id_product IN ('.$params['id_product'].')';
                /*component_routing_db::layer()->insert($sql,array(
                    ':id_product'	    => $data['id_product'],
                    ':name_img'	        => $data['name_img']
                ));*/
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

            if ($config['type'] === 'product') {
                $sql = 'UPDATE mc_catalog_product SET price_p = :price_p, reference_p = :reference_p
                WHERE id_product = :id_product';

            } elseif ($config['type'] === 'content') {
                $sql = 'UPDATE mc_catalog_product_content 
						SET 
							name_p = :name_p,
							url_p = :url_p,
							resume_p = :resume_p,
							content_p = :content_p,
							published_p = :published_p
							WHERE id_product = :id_product 
                		AND id_lang = :id_lang';
            }
            if($sql && $params) component_routing_db::layer()->update($sql,$params);
        }
    }
}