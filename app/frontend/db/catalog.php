<?php
class frontend_db_catalog
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData(array $config, array $params = [])
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'root':
					$sql = 'SELECT d.name_info,d.value_info 
						FROM mc_catalog_data AS d
						JOIN mc_lang AS lang ON(d.id_lang = lang.id_lang)
						WHERE lang.iso_lang = :iso';
					break;
				case 'rootWs':
					$sql = 'SELECT a.*,lang.iso_lang,lang.default_lang
						FROM mc_catalog_data AS a
						JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
					break;
				case 'images':
					$sql = 'SELECT 
							img.id_img,
							img.id_product,
							img.name_img,
							COALESCE(c.alt_img, pc.longname_p, pc.name_p) as alt_img,
							COALESCE(c.title_img, c.alt_img, pc.longname_p, pc.name_p) as title_img,
							COALESCE(c.caption_img, c.title_img, c.alt_img, pc.longname_p, pc.name_p) as caption_img,
							img.default_img,
							img.order_img,
							c.id_lang,
							lang.iso_lang
						FROM mc_catalog_product AS p
						LEFT JOIN mc_catalog_product_content AS pc ON (p.id_product = pc.id_product)
						LEFT JOIN mc_catalog_product_img AS img ON (img.id_product = p.id_product)
						LEFT JOIN mc_catalog_product_img_content AS c ON (img.id_img = c.id_img AND c.id_lang = pc.id_lang)
						LEFT JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
						WHERE img.id_product = :id AND lang.iso_lang = :iso
						ORDER BY img.order_img ASC';
					break;
				case 'catLang':
					$sql = 'SELECT
						h.id_parent,h.id_cat,c.id_lang,c.name_cat,c.url_cat,lang.iso_lang
						FROM mc_catalog_cat AS h
						JOIN mc_catalog_cat_content AS c ON(h.id_cat = c.id_cat) 
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
						WHERE h.id_cat = :id AND c.published_cat = 1';
					break;
				case 'productLang':
					$sql = 'SELECT c.* ,cat.name_cat, cat.url_cat, p.*, pc.name_p, pc.url_p, pc.id_lang,lang.iso_lang, pc.last_update
						FROM mc_catalog AS c
						JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
						JOIN mc_catalog_product AS p ON ( c.id_product = p.id_product )
						JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
						JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang )
						WHERE p.id_product = :id AND cat.published_cat =1 AND pc.published_p =1';
					break;
				/*case 'product_ws':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT p.*,c.*,lang.*
						FROM mc_catalog_product AS p
						JOIN mc_catalog_product_content AS c USING(id_product)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				case 'product_similar_ws':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT rel.*,p.*,c.name_p, c.resume_p, c.content_p, c.url_p,lang.id_lang,lang.iso_lang,default_lang
							FROM mc_catalog_product_rel AS rel
							JOIN mc_catalog_product AS p ON ( rel.id_product_2 = p.id_product )
							JOIN mc_catalog_product_content AS c ON(p.id_product = c.id_product)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				case 'images_ws':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$sql = "SELECT img.* FROM mc_catalog_product_img AS img $conditions";
					break;
				case 'images_content_ws':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT c.*,lang.iso_lang
						FROM mc_catalog_product_img_content AS c
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;*/
				case 'category':
					//$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                    /*$sql = "SELECT cat.*,
								   catc.name_cat,
								   catc.url_cat,
								   catc.resume_cat,
								   catc.content_cat,
								   catc.published_cat,
       								COALESCE(catc.alt_img, catc.name_cat) as alt_img,
									COALESCE(catc.title_img, catc.alt_img, catc.name_cat) as title_img,
									COALESCE(catc.caption_img, catc.title_img, catc.alt_img, catc.name_cat) as caption_img,
								   catc.seo_title_cat,
								   catc.seo_desc_cat,
								   lang.id_lang,
								   lang.iso_lang,
								   lang.default_lang
							FROM mc_catalog_cat AS cat
							JOIN mc_catalog_cat_content AS catc ON(cat.id_cat = catc.id_cat) 
							JOIN mc_lang AS lang ON(catc.id_lang = lang.id_lang) $conditions";*/

                    $where = '';

                    if(isset($params['where']) && is_array($params['where'])) {
                        $newWhere = [];

                        foreach ($params['where'] as $key => $value) {
                            $newWhere[] = array_merge($newWhere, $value);
                        }
                        foreach ($newWhere as $item) {
                            $where .= ' '.$item['type'].' '.$item['condition'].' ';
                        }
                        unset($params['where']);
                    }

                    $select = [
                           'cat.*',
                           'catc.name_cat',
                           'catc.url_cat',
                           'catc.resume_cat',
                           'catc.content_cat',
                           'catc.published_cat',
                            'COALESCE(catc.alt_img, catc.name_cat) as alt_img',
                            'COALESCE(catc.title_img, catc.alt_img, catc.name_cat) as title_img',
                            'COALESCE(catc.caption_img, catc.title_img, catc.alt_img, catc.name_cat) as caption_img',
                           'catc.seo_title_cat',
                           'catc.seo_desc_cat',
                           'lang.id_lang',
                           'lang.iso_lang',
                           'lang.default_lang'/*,
                            '(SELECT count(catalog.id_product) FROM mc_catalog AS catalog WHERE catalog.id_cat = cat.id_cat) as nb_product'*/
                    ];
                    if(isset($params['select'])) {
                        foreach ($params['select'] as $extendSelect) {
                            $select = array_merge($select, $extendSelect);
                        }
                        unset($params['select']);
                    }

                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];

                        foreach ($params['join'] as $key => $value) {
                            $newJoin = array_merge($newJoin, $value);
                        }
                        foreach ($newJoin as $join) {
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
                        }

                        unset($params['join']);
                    }
                    $order = ' ORDER BY cat.order_cat';
                    if(isset($params['order']) && is_array($params['order'])){

                        $order .= ', '.implode(',', $params['order']);

                        unset($params['order']);
                    }

                    $limit = '';
                    if(isset($params['limit']) && is_array($params['limit'])){

                        foreach ($params['limit'] as $item) {
                            $limit = ' LIMIT '.$item;
                        }
                        unset($params['limit']);
                    }
                    $sql = 'SELECT '.implode(',', $select).'
						FROM mc_catalog_cat AS cat
							JOIN mc_catalog_cat_content AS catc ON(cat.id_cat = catc.id_cat) 
							JOIN mc_lang AS lang ON(catc.id_lang = lang.id_lang)'.$joins.$where
                        .$order.$limit;

                    break;
				case 'rand_category':
					$queries = array(
						array('request'=>'CREATE TEMPORARY TABLE cat_map (row_id int not NULL primary key, random_id int not null)'),
						array('request'=>'CREATE TEMPORARY TABLE random_ids (rand_id int auto_increment not NULL primary key, gen_id int not null)'),
						array('request'=>'SET @id = 0'),
						array('request'=>'
									INSERT INTO cat_map 
									SELECT @id := @id + 1, p.id_cat 
									FROM mc_catalog_cat AS p 
									JOIN mc_catalog_cat_content AS pc ON ( p.id_cat = pc.id_cat )
									JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) WHERE pc.published_cat = 1 and lang.iso_lang = :iso',
							'params'=>array('iso' => $params['iso'])),
						array('request'=>'INSERT INTO random_ids (gen_id) VALUES '.$params['ids']),
						array('request'=>"
							SELECT rows.random_id
							FROM cat_map as rows
							JOIN random_ids as ids ON(rows.row_id = ids.gen_id)
						",'fetch'=>true)
					);

					try {
						$result = component_routing_db::layer()->transaction($queries);
						return $result[5];
					}
					catch (Exception $e) {
						return 'Exception reçue : '.$e->getMessage();
					}
					break;
				case 'category_short':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT p.id_cat,
       								p.id_parent,
								   c.name_cat,
								   c.url_cat,
								   c.seo_title_cat,
								   lang.iso_lang
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c ON(p.id_cat = c.id_cat) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				case 'parents':
					$sql = "SELECT t.id_cat AS parent, GROUP_CONCAT(f.id_cat) AS children
								FROM mc_catalog_cat t
								JOIN mc_catalog_cat f ON t.id_cat=f.id_parent
								GROUP BY t.id_cat";
					break;
				case 'product':
					//$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                    $where = '';
                    if(isset($params['where']) && is_array($params['where'])) {
                        $newWhere = [];

                        foreach ($params['where'] as $key => $value) {
                            $newWhere = array_merge($newWhere, $value);
                        }
                        foreach ($newWhere as $item) {
                            $where .= ' '.$item['type'].' '.$item['condition'].' ';
                        }
                        unset($params['where']);
                    }

                    $select = ['catalog.*',
                        'cat.name_cat',
                        'cat.url_cat',
                        'p.*',
                        'pc.name_p',
                        'pc.longname_p',
                        'pc.resume_p',
                        'pc.content_p',
                        'pc.url_p',
                        'pc.id_lang',
                        'lang.iso_lang',
                        'pc.last_update',
                        'img.name_img',
                        'COALESCE(imgc.alt_img, pc.longname_p, pc.name_p) as alt_img',
                        'COALESCE(imgc.title_img, imgc.alt_img, pc.longname_p, pc.name_p) as title_img',
                        'COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, pc.longname_p, pc.name_p) as caption_img',
                        'pc.seo_title_p',
                        'pc.seo_desc_p'];

                    if(isset($params['select'])) {
                        foreach ($params['select'] as $extendSelect) {
                            $select = array_merge($select, $extendSelect);
                        }
                        unset($params['select']);
                    }

                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];

                        foreach ($params['join'] as $key => $value) {
                            $newJoin = array_merge($newJoin, $value);
                        }
                        foreach ($newJoin as $join) {
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
                        }

                        unset($params['join']);
                    }
                    if($params['id_cat']){
                        $cat = 'AND catalog.default_c = 1 AND catalog.id_product IN (SELECT id_product FROM mc_catalog WHERE id_cat = :id_cat)';
                    }else{
                        $cat = '';
                    }

                    $order = ' ORDER BY catalog.order_p ASC';
                    if(isset($params['order']) && is_array($params['order'])){

                        $order .= ', '.implode(',', $params['order']);

                        unset($params['order']);
                    }

                    $limit = '';
                    if(isset($params['limit']) && is_array($params['limit'])){

                        foreach ($params['limit'] as $item) {
                            $limit = ' LIMIT '.$item;
                        }
                        unset($params['limit']);
                    }

                    $sql = 'SELECT '.implode(',', $select).'
						FROM mc_catalog AS catalog
						JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
						JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
						JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
						JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
						LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
						LEFT JOIN mc_catalog_product_img_content AS imgc ON (imgc.id_img = img.id_img and pc.id_lang = imgc.id_lang)
						JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang)'.$joins.'
						 WHERE lang.iso_lang = :iso 
						AND pc.published_p = 1 
						AND (img.default_img = 1 OR img.default_img IS NULL) 
						 '.$cat.$where
                        .$order.$limit;
					break;

				case 'rand_product':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$queries = array(
						array('request'=>'CREATE TEMPORARY TABLE product_map (row_id int not NULL primary key, random_id int not null)'),
						array('request'=>'CREATE TEMPORARY TABLE random_ids (rand_id int auto_increment not NULL primary key, gen_id int not null)'),
						array('request'=>'SET @id = 0'),
						array('request'=>'
									INSERT INTO product_map 
									SELECT @id := @id + 1, p.id_product 
									FROM mc_catalog_product AS p 
									JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
									JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) WHERE pc.published_p = 1 and lang.iso_lang = :iso',
							'params'=>array('iso' => $params['iso'])),
						array('request'=>'INSERT INTO random_ids (gen_id) VALUES '.$params['ids']),
						array('request'=>"
							SELECT 
							   	catalog.* ,
								cat.name_cat, 
								cat.url_cat, 
								p.*, 
								pc.name_p, 
								pc.longname_p, 
								pc.resume_p, 
								pc.content_p, 
								pc.url_p, 
								pc.id_lang,
								lang.iso_lang, 
								pc.last_update, 
								img.name_img,
								COALESCE(imgc.alt_img, pc.longname_p, pc.name_p) as alt_img,
								COALESCE(imgc.title_img, imgc.alt_img, pc.longname_p, pc.name_p) as title_img,
								COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, pc.longname_p, pc.name_p) as caption_img,
								pc.seo_title_p,
								pc.seo_desc_p 
							FROM mc_catalog AS catalog 
							JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
							JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
							JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
							JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
							LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
							LEFT JOIN mc_catalog_product_img_content AS imgc ON (imgc.id_img = img.id_img and pc.id_lang = imgc.id_lang)
							JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang)
							JOIN product_map as rows ON (p.id_product = rows.random_id)
							JOIN random_ids as ids ON(rows.row_id = ids.gen_id)
							$conditions
						",'params'=>array('iso' => $params['iso']),'fetch'=>true)
					);

					try {
						$result = component_routing_db::layer()->transaction($queries);
						return $result[5];
					}
					catch (Exception $e) {
						return 'Exception reçue : '.$e->getMessage();
					}
					break;
				case 'product_short':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT 
								catalog.id_product,
								catalog.id_cat,
								cat.name_cat,
								cat.url_cat, 
								pc.name_p, 
								pc.url_p,
								lang.iso_lang, 
								pc.seo_title_p
						FROM mc_catalog AS catalog
						JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
						JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
						JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
						JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
						LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
						LEFT JOIN mc_catalog_product_img_content AS imgc ON (imgc.id_img = img.id_img and pc.id_lang = imgc.id_lang)
						JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang) $conditions";
					break;
				case 'similar':
					$sql = 'SELECT 
                            cat.name_cat, 
                           cat.url_cat, 
                           catalog.id_cat, 
                           p.*, 
                            pc.name_p,  
                            pc.resume_p, 
                            pc.content_p, 
                           pc.url_p, 
                           pc.id_lang,
                           lang.iso_lang, 
                           pc.last_update, 
                           img.name_img,
       						COALESCE(imgc.alt_img, pc.longname_p, pc.name_p) as alt_img,
							COALESCE(imgc.title_img, imgc.alt_img, pc.longname_p, pc.name_p) as title_img,
							COALESCE(imgc.caption_img, imgc.title_img, imgc.alt_img, pc.longname_p, pc.name_p) as caption_img,
						   	pc.seo_title_p,
						   	pc.seo_desc_p
						FROM mc_catalog_product_rel AS rel
						JOIN mc_catalog AS catalog ON (rel.id_product_2 = catalog.id_product)
						JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
						JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
						JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
						JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
						LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
						LEFT JOIN mc_catalog_product_img_content AS imgc ON (imgc.id_img = img.id_img and pc.id_lang = imgc.id_lang)
						JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang)
						WHERE rel.id_product = :id AND lang.iso_lang = :iso AND catalog.default_c = 1 AND (img.default_img = 1 OR img.default_img IS NULL)';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				/*case 'cat':
					$sql = 'SELECT p.*,
       							   c.name_cat,
								   c.url_cat,
								   c.resume_cat,
								   c.content_cat,
								   c.published_cat,
       								COALESCE(c.alt_img, c.name_cat) as alt_img,
									COALESCE(c.title_img, c.alt_img, c.name_cat) as title_img,
									COALESCE(c.caption_img, c.title_img, c.alt_img, c.name_cat) as caption_img,
								   c.seo_title_cat,
								   c.seo_desc_cat,
       							   lang.*
						FROM mc_catalog_cat AS p
						JOIN mc_catalog_cat_content AS c USING(id_cat)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE p.id_cat = :id AND lang.iso_lang = :iso AND c.published_cat = 1';
					break;*/
                case 'childCat':
                    $sql = "SELECT GROUP_CONCAT( cat.id_cat SEPARATOR ',' ) AS child
                    FROM mc_catalog_cat AS cat WHERE cat.id_parent = :id_parent OR cat.id_cat = :id";
                    break;
                case 'nbProduct':
                    $where = '';
                    //print_r($params['where']);
                    if(isset($params['where']) && is_array($params['where'])) {
                        $newWhere = [];

                        foreach ($params['where'] as $key => $value) {
                            $newWhere = array_merge($newWhere, $value);
                        }
                        foreach ($newWhere as $item) {
                            $where .= ' '.$item['type'].' '.$item['condition'].' ';
                        }
                        unset($params['where']);
                    }
                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];

                        foreach ($params['join'] as $key => $value) {
                            $newJoin = array_merge($newJoin, $value);
                        }
                        foreach ($newJoin as $join) {
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
                        }

                        unset($params['join']);
                    }
                    print $sql = 'SELECT count(catalog.id_catalog) AS nb_product
                    FROM mc_catalog AS catalog '.$joins.' WHERE catalog.id_cat IN ('.$params['id_cat'].') '.$where;
                    $params = array();
                    break;
                case 'category':
                    $cond = '';
                    if(isset($params['where'])) {
                        unset($params['where']);
                    }

                    $select = ['cat.*',
                               'catc.name_cat',
                               'catc.url_cat',
                               'catc.resume_cat',
                               'catc.content_cat',
                               'catc.published_cat',
                                'COALESCE(catc.alt_img, catc.name_cat) as alt_img',
                                'COALESCE(catc.title_img, catc.alt_img, catc.name_cat) as title_img',
                                'COALESCE(catc.caption_img, catc.title_img, catc.alt_img, catc.name_cat) as caption_img',
                               'catc.seo_title_cat',
                               'catc.seo_desc_cat',
                               'lang.*'
                    ];
                    if(isset($params['select'])) {
                        foreach ($params['select'] as $extendSelect) {
                            $select = array_merge($select, $extendSelect);
                        }
                        unset($params['select']);
                    }

                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];

                        foreach ($params['join'] as $key => $value) {
                            $newJoin = array_merge($newJoin, $value);
                        }
                        foreach ($newJoin as $join) {
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
                        }

                        unset($params['join']);
                    }

                    $sql = 'SELECT '.implode(',', $select).
                        ' FROM mc_catalog_cat AS cat
						JOIN mc_catalog_cat_content AS catc ON (cat.id_cat = catc.id_cat)
						JOIN mc_lang AS lang ON(catc.id_lang = lang.id_lang)
						'.$joins.
                        ' WHERE cat.id_cat = :id AND lang.iso_lang = :iso AND catc.published_cat = 1 '.$cond;

                    break;
				case 'product':
                    $cond = '';
                    if(isset($params['where'])) {
                        unset($params['where']);
                    }

                    $select = ['c.*' ,
							   'cat.name_cat',
							   'cat.url_cat',
							   'p.*',
							   'pc.name_p',
							   'pc.longname_p',
							   'pc.resume_p',
							   'pc.content_p',
							   'pc.url_p',
							   'pc.id_lang',
							   'pc.seo_title_p',
							   'pc.seo_desc_p',
							   'lang.iso_lang',
							   'pc.last_update'
                    ];
                    if(isset($params['select'])) {
                        foreach ($params['select'] as $extendSelect) {
                            $select = array_merge($select, $extendSelect);
                        }
                        unset($params['select']);
                    }

                    $joins = '';
                    if(isset($params['join']) && is_array($params['join'])) {
                        $newJoin = [];

                        foreach ($params['join'] as $key => $value) {
                            $newJoin = array_merge($newJoin, $value);
                        }
                        foreach ($newJoin as $join) {
                            $joins .= ' '.$join['type'].' '.$join['table'].' '.$join['as'].' ON ('.$join['on']['table'].'.'.$join['on']['key'].' = '.$join['as'].'.'.$join['on']['key'].') ';
                        }

                        unset($params['join']);
                    }

                    $sql = 'SELECT '.implode(',', $select).

							' FROM mc_catalog AS c
							JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
							JOIN mc_catalog_product AS p ON ( c.id_product = p.id_product )
							JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
							JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang )'.$joins.
							' WHERE p.id_product = :id AND c.default_c =1 AND cat.published_cat =1 AND pc.published_p =1 AND lang.iso_lang = :iso'.$cond;

                    break;
				case 'root':
					$sql = 'SELECT * FROM `mc_catalog_data` WHERE `id_lang` = :id_lang';
					break;
				case 'tot_product':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT 
								COUNT(DISTINCT p.id_product) as tot
						FROM mc_catalog AS catalog
						JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
						JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
						JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
						JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
						LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
						LEFT JOIN mc_catalog_product_img_content AS imgc ON (imgc.id_img = img.id_img and pc.id_lang = imgc.id_lang)
						JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang) $conditions";
					break;
				case 'tot_cat':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT COUNT(DISTINCT p.id_cat) as tot
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c ON(p.id_cat = c.id_cat) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'newContent':
                $queries = array(
                    array(
                        'request' => "SET @lang = :id_lang",
                        'params' => array('id_lang' => $params['id_lang'])
                    ),
                    array(
                        'request' => "INSERT INTO `mc_catalog_data` (`id_lang`,`name_info`,`value_info`) VALUES
							(@lang,'name',:nm),(@lang,'content',:content),(@lang,'seo_desc',:seo_desc),(@lang,'seo_title',:seo_title)",
                        'params' => array(
                            'nm'        => $params['name'],
                            'content'   => $params['content'],
                            'seo_desc'  => $params['seo_desc'],
                            'seo_title' => $params['seo_title']
                        )
                    ),
                );

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'content':
				$sql = "UPDATE `mc_catalog_data`
                        SET `value_info` = CASE `name_info`
                            WHEN 'name' THEN :nm
                            WHEN 'content' THEN :content
                            WHEN 'seo_desc' THEN :seo_desc
						    WHEN 'seo_title' THEN :seo_title
                        END
                        WHERE `name_info` IN ('name','content','seo_desc','seo_title') AND id_lang = :id_lang";
				$params = array(
					'nm'        => $params['name'],
					'content'   => $params['content'],
                    'seo_title' => $params['seo_title'],
                    'seo_desc'  => $params['seo_desc'],
					'id_lang'   => $params['id_lang']
				);
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}