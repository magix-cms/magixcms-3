<?php
class backend_db_category
{
    public function fetchData($config, $data = false){
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();
        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'pages') {
					$params = $data;
                    $sql = "SELECT p.id_cat, c.name_cat, c.content_cat, p.menu_cat, p.date_register, p.img_cat
								FROM mc_catalog_cat AS p
									JOIN mc_catalog_cat_content AS c USING ( id_cat )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
									GROUP BY p.id_cat 
								ORDER BY p.order_cat";
                    if(isset($config['search'])) {
                        $cond = '';
                        $config['search'] = array_filter($config['search']);
                        if(is_array($config['search']) && !empty($config['search'])) {
                            $nbc = 0;
                            foreach ($config['search'] as $key => $q) {
                                if($q != '') {
                                    $cond .= 'AND ';
									$params[$key] = $q;
                                    switch ($key) {
                                        case 'id_cat':
                                        case 'published_cat':
                                            $cond .= 'c.'.$key.' = '.$q.' ';
                                            break;
                                        case 'name_cat':
                                            $cond .= "c.".$key." LIKE '%".$q."%' ";
                                            break;
                                        case 'parent_cat':
                                            $cond .= "ca.name_cat"." LIKE '%".$q."%' ";
                                            break;
                                        case 'date_register':
                                            $q = $dateFormat->date_to_db_format($q);
                                            $cond .= "p.".$key." LIKE '%".$q."%' ";
											//$params[$key] = $q;
                                            break;
                                        case 'menu_cat':
                                            $cond .= 'p.'.$key.' = '.$q.' ';
                                            break;
                                    }
                                    $nbc++;
                                }
                            }

                            $sql = "SELECT p.id_cat, c.name_cat, c.content_cat, p.menu_cat, p.date_register, p.img_cat, ca.name_cat AS parent_cat
								FROM mc_catalog_cat AS p
									JOIN mc_catalog_cat_content AS c USING ( id_cat )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
									LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
									WHERE c.id_lang = :default_lang $cond
									GROUP BY p.id_cat 
								ORDER BY p.order_cat";
                        }
                    }
                }
                elseif ($config['type'] === 'pagesChild') {
                    $cond = '';
					$params = $data;
                    if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                        $nbc = 0;
                        foreach ($config['search'] as $key => $q) {
                            if($q != '') {
                                $cond .= 'AND ';
								//$params[$key] = $q;
                                switch ($key) {
                                    case 'id_cat':
                                        $cond .= 'c.'.$key.' = '.$q.' ';
                                        break;
                                    case 'name_cat':
                                        $cond .= "c.".$key." LIKE '%".$q."%' ";
                                        break;
                                    case 'date_register':
                                        $q = $dateFormat->date_to_db_format($q);
                                        $cond .= "p.".$key." LIKE '%".$q."%' ";
                                        $params[$key] = $q;
                                        break;
                                    case 'menu_cat':
                                        $cond .= 'p.'.$key.' = '.$q.' ';
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT p.id_cat, c.name_cat,p.menu_cat, p.date_register,p.img_cat
							FROM mc_catalog_cat AS p
								JOIN mc_catalog_cat_content AS c USING ( id_cat )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
								LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
								WHERE p.id_parent = :id $cond
								GROUP BY p.id_cat 
							ORDER BY p.order_cat";
                }
                elseif ($config['type'] === 'pagesSelect') {
                    //List pages for select
                    $sql = "SELECT p.id_parent,p.id_cat, c.name_cat , ca.name_cat AS parent_cat
							FROM mc_catalog_cat AS p
								JOIN mc_catalog_cat_content AS c USING ( id_cat )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
								LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
								WHERE c.id_lang = :default_lang
								GROUP BY p.id_cat 
							ORDER BY p.id_cat DESC";
                    $params = $data;
                }
                elseif ($config['type'] === 'page') {
                    $sql = 'SELECT p.*,c.*,lang.*
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c USING(id_cat)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_cat = :edit';
                    $params = $data;
                }
                elseif ($config['type'] === 'img') {
                    $sql = 'SELECT p.id_cat, p.img_cat
                        	FROM mc_catalog_cat AS p WHERE p.img_cat IS NOT NULL';
                }
                elseif ($config['type'] === 'catRoot') {
                    $sql = 'SELECT DISTINCT c.id_cat, c.id_parent, cont.name_cat, cc.id_parent AS parent_id
							FROM mc_catalog_cat AS c
							LEFT JOIN mc_catalog_cat AS cc ON ( cc.id_parent = c.id_cat )
							LEFT JOIN mc_catalog_cat_content AS cont ON ( c.id_cat = cont.id_cat )
							LEFT JOIN mc_lang AS lang ON ( cont.id_lang = lang.id_lang )
							WHERE cont.id_lang = :default_lang AND c.id_parent IS NULL';
                    $params = $data;
                }
                elseif ($config['type'] === 'cats') {
                    $sql = 'SELECT DISTINCT c.id_cat, c.id_parent, cont.name_cat
							FROM mc_catalog_cat AS c
							LEFT JOIN mc_catalog_cat_content AS cont ON ( c.id_cat = cont.id_cat )
							LEFT JOIN mc_lang AS lang ON ( cont.id_lang = lang.id_lang )
							WHERE cont.id_lang = :default_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'subcat') {
                    $sql = 'SELECT c.id_parent,c.id_cat, cont.name_cat, cc.id_parent AS parent_id
							FROM mc_catalog_cat AS c
							LEFT JOIN mc_catalog_cat AS cc ON ( cc.id_parent = c.id_cat )
							LEFT JOIN mc_catalog_cat_content AS cont ON ( c.id_cat = cont.id_cat )
							LEFT JOIN mc_lang AS lang ON ( cont.id_lang = lang.id_lang )
							WHERE cont.id_lang = :default_lang AND c.id_parent = :id';
                    $params = $data;

                }
                elseif ($config['type'] === 'catalog') {
                    $sql = 'SELECT catalog.id_catalog, catalog.id_product, p_cont.name_p, catalog.order_p, lang.id_lang,lang.iso_lang
							FROM mc_catalog AS catalog
							JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
							JOIN mc_catalog_product_content AS p_cont ON ( p_cont.id_product = p.id_product )
							JOIN mc_lang AS lang ON ( p_cont.id_lang = lang.id_lang ) 
							WHERE catalog.id_cat = :id_cat AND p_cont.id_lang = :default_lang
							ORDER BY catalog.order_p';
                    $params = $data;
                }
				elseif ($config['type'] === 'lastCats') {
					//### -- Dashboard Data
					$sql = "SELECT p.id_cat, c.name_cat, p.date_register
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c USING ( id_cat )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang
							GROUP BY p.id_cat 
							ORDER BY p.id_cat DESC
							LIMIT 5";
					$params = $data;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_catalog_cat ORDER BY id_cat DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_catalog_cat_content` WHERE `id_cat` = :id_cat AND `id_lang` = :id_lang';
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
     * @throws Exception
     */
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            $sql = '';
            $params = $data;

            if ($config['type'] === 'page') {
                $sql = 'UPDATE mc_catalog_cat 
							SET 
								id_parent = :id_parent
							WHERE id_cat = :id_cat';

                component_routing_db::layer()->update($sql,$data);
            }
            elseif ($config['type'] === 'content') {
                $sql = 'UPDATE mc_catalog_cat_content SET name_cat = :name_cat, url_cat = :url_cat, resume_cat = :resume_cat, content_cat=:content_cat, published_cat=:published_cat
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
            }elseif ($config['type'] === 'order_p') {
                $sql = 'UPDATE mc_catalog SET order_p = :order_p
                WHERE id_catalog = :id_catalog';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_catalog'	=> $data['id_catalog'],
                        ':order_p'	=> $data['order_p']
                    )
                );
            }
            elseif ($config['type'] === 'pageActiveMenu') {
                $sql = 'UPDATE mc_catalog_cat 
						SET menu_cat = :menu_cat 
						WHERE id_cat IN ('.$data['id_cat'].')';
                //if($sql && $params) component_routing_db::layer()->update($sql,$params);
                component_routing_db::layer()->update($sql,
                    array(
                        ':menu_cat'	=> $data['menu_cat']
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


            }elseif ($config['type'] === 'newContent') {

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