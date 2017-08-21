<?php
class backend_db_product{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();
        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'pages') {
                    $sql = "SELECT p.id_product, c.name_p, p.reference_p, p.price_p, c.content_p, p.date_register
								FROM mc_catalog_product AS p
									JOIN mc_catalog_product_content AS c USING ( id_product )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang
									GROUP BY p.id_product 
								ORDER BY p.id_product";
                    if (isset($config['search'])) {
                        $cond = '';
                        $config['search'] = array_filter($config['search']);
                        if (is_array($config['search']) && !empty($config['search'])) {
                            $nbc = 0;
                            foreach ($config['search'] as $key => $q) {
                                if ($q != '') {
                                    $cond .= 'AND ';
                                    switch ($key) {
                                        case 'id_product':
                                        case 'published_p':
                                            $cond .= 'c.' . $key . ' = ' . $q . ' ';
                                            break;
                                        case 'name_p':
                                            $cond .= "c." . $key . " LIKE '%" . $q . "%' ";
                                            break;
                                        case 'reference_p':
                                            $cond .= "p." . $key . " LIKE '%" . $q . "%' ";
                                            break;
                                        case 'date_register':
                                            $q = $dateFormat->date_to_db_format($q);
                                            $cond .= "p." . $key . " LIKE '%" . $q . "%' ";
                                            break;
                                    }
                                    $nbc++;
                                }
                            }

                            $sql = "SELECT p.id_product, c.name_p, p.reference_p, p.price_p ,c.content_p, p.date_register
								FROM mc_catalog_product AS p
									JOIN mc_catalog_product_content AS c USING ( id_product )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang $cond
									GROUP BY p.id_product 
								ORDER BY p.id_product";
                        }
                    }
                    $params = $data;
                }elseif ($config['type'] === 'page') {
                    $sql = 'SELECT p.*,c.*,lang.*
                        FROM mc_catalog_product AS p
                        JOIN mc_catalog_product_content AS c USING(id_product)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE p.id_product = :edit';

                    $params = $data;

                }elseif ($config['type'] === 'editImages') {
                    $sql = 'SELECT img.*
                        FROM mc_catalog_product_img AS img
                        WHERE img.id_product = :edit';

                    $params = $data;

                }
                elseif ($config['type'] === 'imagesAll') {
                    $sql = 'SELECT img.*
                        FROM mc_catalog_product_img AS img';

                }
                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }elseif ($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
                    //$params = $data;
                } elseif ($config['type'] === 'content') {

                    $sql = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
                    $params = $data;

                } elseif ($config['type'] === 'page') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id_product';
                    $params = $data;
                }

                elseif ($config['type'] === 'img') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_catalog_product_img WHERE `id_img` = :editimg';
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
            if ($config['type'] === 'product') {
                $sql = 'UPDATE mc_catalog_product SET price_p = :price_p, reference_p = :reference_p
                WHERE id_product = :id_product';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_product'	=> $data['id_product'],
                        ':price_p'      => $data['price_p'],
                        ':reference_p'  => $data['reference_p']
                    )
                );
            }elseif ($config['type'] === 'content') {
                $sql = 'UPDATE mc_catalog_product_content SET name_p = :name_p, url_p = :url_p, content_p = :content_p, published_p = :published_p
                WHERE id_product = :id_product AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	    => $data['id_lang'],
                        ':id_product'	=> $data['id_product'],
                        ':name_p'       => $data['name_p'],
                        ':url_p'        => $data['url_p'],
                        ':content_p'    => $data['content_p'],
                        ':published_p'  => $data['published_p']
                    )
                );
            }elseif ($config['type'] === 'img') {

                $sql = 'UPDATE mc_catalog_product_img SET alt_img = :alt_img, title_img = :title_img
                WHERE id_img = :id_img';

                component_routing_db::layer()->update($sql,
                    array(
                        ':id_img'	    => $data['id_img'],
                        ':alt_img'      => $data['alt_img'],
                        ':title_img'    => $data['title_img']
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

                $sql = 'INSERT INTO `mc_catalog_product`(price_p,reference_p,date_register) 
                VALUES (:price_p,:reference_p,NOW())';
                component_routing_db::layer()->insert($sql,array(
                    ':price_p'	        => $data['price_p'],
                    ':reference_p'	    => $data['reference_p']
                ));


            }elseif ($config['type'] === 'newContent') {

                $sql = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,url_p,content_p,published_p) 
				  VALUES (:id_product,:id_lang,:name_p,:url_p,:content_p,:published_p)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_lang'	    => $data['id_lang'],
                    ':id_product'	=> $data['id_product'],
                    ':name_p'       => $data['name_p'],
                    ':url_p'        => $data['url_p'],
                    ':content_p'    => $data['content_p'],
                    ':published_p'  => $data['published_p']
                ));
            }elseif ($config['type'] === 'img') {

                $sql = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img) 
                VALUES (:id_product,:name_img)';
                component_routing_db::layer()->insert($sql,array(
                    ':id_product'	    => $data['id_product'],
                    ':name_img'	        => $data['name_img']
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
            if($config['type'] === 'delPages') {
                $sql = 'DELETE FROM mc_catalog_product WHERE id_product IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
            elseif($config['type'] === 'delImages') {
				$sql = 'DELETE FROM mc_catalog_product_img WHERE id_img IN ('.$data['id'].')';
				component_routing_db::layer()->delete($sql,array());
			}
        }
    }
}
?>