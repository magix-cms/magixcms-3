<?php
class backend_db_product{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';
		$dateFormat = new component_format_date();

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$sql = "SELECT p.id_product, c.name_p, p.reference_p, p.price_p, c.resume_p, c.content_p, p.date_register
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
									$p = 'p'.$nbc;
									switch ($key) {
										case 'id_product':
											$cond .= 'p.'.$key.' = :'.$p.' ';
											break;
										case 'published_p':
											$cond .= 'c.'.$key.' = :'.$p.' ';
											break;
										case 'name_p':
											$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'date_register':
											$q = $dateFormat->date_to_db_format($q);
										case 'reference_p':
											$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
									}
									$params[$p] = $q;
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
					break;
				case 'page':
					$sql = 'SELECT p.*,c.*,lang.*
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS c USING(id_product)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_product = :edit';
					break;
				case 'images':
					$sql = 'SELECT img.*
						FROM mc_catalog_product_img AS img
						WHERE img.id_product = :id ORDER BY order_img ASC';
					break;
				case 'imagesAll':
					$sql = 'SELECT img.* FROM mc_catalog_product_img AS img';
					break;
				case 'catRel':
					$sql = 'SELECT id_product, id_cat, default_c FROM mc_catalog WHERE id_product = :id';
					break;
				case 'productRel':
					$sql = 'SELECT rel.*,c.name_p
							FROM mc_catalog_product_rel AS rel
							JOIN mc_catalog_product_content AS c ON(rel.id_product_2 = c.id_product)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE rel.id_product = :id AND c.id_lang = :default_lang';
					break;
				case 'imgData':
					$sql = 'SELECT img.id_img,img.id_product, img.name_img,c.id_lang,c.alt_img,c.title_img,lang.iso_lang
							FROM mc_catalog_product_img AS img
							LEFT JOIN mc_catalog_product_img_content AS c USING(id_img)
							LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE img.id_img = :edit';
					break;
				case 'lastProducts':
					$sql = "SELECT p.id_product, c.name_p, p.date_register
						FROM mc_catalog_product AS p
						JOIN mc_catalog_product_content AS c USING ( id_product )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE c.id_lang = :default_lang
						GROUP BY p.id_product 
						ORDER BY p.id_product DESC
						LIMIT 5";
					break;
				case 'pagesPublishedSelect':
					$sql = "SELECT 
								p.id_product,
								pc.name_p as name_product,
								cc.id_cat as id_parent,
								ccc.name_cat as name_parent,
								lang.iso_lang
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							JOIN mc_catalog_cat AS cc ON c.id_cat = cc.id_cat
							JOIN mc_catalog_cat_content AS ccc ON (cc.id_cat = ccc.id_cat AND pc.id_lang = ccc.id_lang)
							JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
							WHERE pc.id_lang = :default_lang
							AND pc.published_p = 1
							ORDER BY cc.id_cat ASC, p.id_product ASC";
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$sql = 'SELECT id_product FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
					break;
				case 'page':
					$sql = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id_product';
					break;
				case 'rootImg':
					$sql = 'SELECT id_img FROM mc_catalog_product_img WHERE id_product = :id ORDER BY id_img DESC LIMIT 0,1';
					break;
				case 'imgContent':
					$sql = 'SELECT * FROM mc_catalog_product_img_content WHERE `id_img` = :id_img AND `id_lang` = :id_lang';
					break;
				case 'img':
					$sql = 'SELECT * FROM mc_catalog_product_img WHERE `id_img` = :id';
					break;
				case 'imgDefault':
					$sql = 'SELECT id_img FROM mc_catalog_product_img WHERE id_product = :id AND default_img = 1';
					break;
				case 'catRel':
					$sql = 'SELECT * FROM mc_catalog WHERE id_product = :id AND id_cat = :id_cat';
					break;
				case 'lastProductRel':
					$sql = 'SELECT rel.*,c.name_p
						FROM mc_catalog_product_rel AS rel
						JOIN mc_catalog_product_content AS c ON(rel.id_product_2 = c.id_product)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
						WHERE rel.id_product = :id AND c.id_lang = :default_lang
						ORDER BY rel.id_rel DESC LIMIT 0,1';
					break;
				case 'pageLang':
					$sql = 'SELECT 
								p.id_product,
								pc.name_p,
								cc.id_cat as id_parent,
								ccc.name_cat as name_parent,
								lang.iso_lang
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS pc USING ( id_product )
							JOIN mc_catalog AS c ON ( p.id_product = c.id_product AND c.default_c = 1 )
							JOIN mc_catalog_cat AS cc ON c.id_cat = cc.id_cat
							JOIN mc_catalog_cat_content AS ccc ON (cc.id_cat = ccc.id_cat AND pc.id_lang = ccc.id_lang)
							JOIN mc_lang AS lang ON(pc.id_lang = lang.id_lang)
							WHERE p.id_product = :id
							AND lang.iso_lang = :iso';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
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
			case 'newPages':
				$sql = 'INSERT INTO `mc_catalog_product`(price_p,reference_p,date_register) 
						VALUES (:price_p,:reference_p,NOW())';
				break;
			case 'newContent':
				$sql = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,url_p,resume_p,content_p,published_p) 
			  			VALUES (:id_product,:id_lang,:name_p,:url_p,:resume_p,:content_p,:published_p)';
				break;
			case 'newImg':
				$sql = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img,order_img,default_img) 
						SELECT :id_product,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_catalog_product_img WHERE id_product IN ('.$params['id_product'].')';
				break;
			case 'newImgContent':
				$sql = 'INSERT INTO `mc_catalog_product_img_content`(id_img,id_lang,alt_img,title_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img)';
				break;
			case 'catRel':
				$sql = 'INSERT INTO `mc_catalog` (id_product,id_cat,default_c,order_p)
						SELECT :id,:id_cat,:default_c,COUNT(id_catalog) FROM mc_catalog WHERE id_cat IN ('.$params[':id_cat'].')';
				break;
			case 'productRel':
				$sql = 'INSERT INTO `mc_catalog_product_rel` (id_product,id_product_2)
					VALUES (:id_product,:id_product_2)';
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
			case 'product':
				$sql = 'UPDATE mc_catalog_product SET price_p = :price_p, reference_p = :reference_p
                WHERE id_product = :id_product';
				break;
			case 'content':
				$sql = 'UPDATE mc_catalog_product_content 
						SET 
							name_p = :name_p,
							url_p = :url_p,
							resume_p = :resume_p,
							content_p = :content_p,
							published_p = :published_p
							WHERE id_product = :id_product 
                		AND id_lang = :id_lang';
				break;
			case 'imgContent':
				$sql = 'UPDATE mc_catalog_product_img_content SET alt_img = :alt_img, title_img = :title_img
                WHERE id_img = :id_img AND id_lang = :id_lang';
				break;
			case 'catRel':
				$sql = 'UPDATE mc_catalog
                		SET default_c = CASE id_cat
							WHEN :id_cat THEN 1
							ELSE 0
						END
						WHERE id_product = :id';
				break;
			case 'imageDefault':
				$sql = 'UPDATE mc_catalog_product_img
                		SET default_img = CASE id_img
							WHEN :id_img THEN 1
							ELSE 0
						END
						WHERE id_product = :id';
				break;
			case 'firstImageDefault':
				$sql = 'UPDATE mc_catalog_product_img
                		SET default_img = 1
                		WHERE id_product = :id 
						ORDER BY order_img ASC 
						LIMIT 1';
				break;
			case 'order':
				$sql = 'UPDATE mc_catalog_product_img SET order_img = :order_img
                		WHERE id_img = :id_img';
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

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';
		$sql = '';

		switch ($config['type']) {
			case 'delPages':
				$sql = 'DELETE FROM `mc_catalog_product` WHERE `id_product` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delImages':
				$sql = 'DELETE FROM `mc_catalog_product_img` WHERE `id_img` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'catRel':
				$sql = 'DELETE FROM mc_catalog WHERE id_product = :id';
				break;
			case 'oldCatRel':
				$sql = 'DELETE FROM mc_catalog WHERE id_product = '.$params[':id'].' AND id_cat NOT IN ('.$params[':id_cat'].')';
				$params = array();
				break;
			case 'productRel':
				$sql = 'DELETE FROM `mc_catalog_product_rel` WHERE `id_rel` IN ('.$params['id'].')';
				$params = array();
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}