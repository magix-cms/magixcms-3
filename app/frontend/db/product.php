<?php
class frontend_db_product
{
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

        if (is_array($config)) {
            if ($config['context'] === 'all') {
            	switch ($config['type']) {
                    case 'pages':
                        $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                        $sql = "SELECT p.*,c.*,lang.*
						FROM mc_catalog_product AS p
						JOIN mc_catalog_product_content AS c USING(id_product)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
                        break;
                    case 'similar':
                        $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                        $sql = "SELECT rel.*,p.*,c.name_p, c.resume_p, c.content_p, c.url_p,lang.id_lang,lang.iso_lang,default_lang
							FROM mc_catalog_product_rel AS rel
							JOIN mc_catalog_product AS p ON ( rel.id_product_2 = p.id_product )
							JOIN mc_catalog_product_content AS c ON(p.id_product = c.id_product)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
                        break;
                    case 'images':
                        $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                        $sql = "SELECT img.* FROM mc_catalog_product_img AS img $conditions";
                        break;
                    case 'images_content':
                        $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                        $sql = "SELECT c.*,lang.iso_lang
						FROM mc_catalog_product_img_content AS c
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
                        break;
                }

				return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
            	switch ($config['type']) {
					case 'root':
						$sql = 'SELECT id_product FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
						break;
					case 'page':
						$sql = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id';
						break;
					case 'content':
						$sql = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
						break;
					case 'category':
						$sql = 'SELECT * FROM mc_catalog WHERE `id_product` = :id AND id_cat = :id_cat';
						break;
					case 'img':
						$sql = 'SELECT * FROM mc_catalog_product_img WHERE `name_img` = :name_img';
						break;
                    case 'lastImgId':
                        $sql = 'SELECT id_img FROM mc_catalog_product_img ORDER BY id_img DESC LIMIT 0,1';
                        break;
            	}

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
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
                $sql = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,longname_p,url_p,resume_p,content_p,seo_title_p,seo_desc_p,published_p) 
			  			VALUES (:id_product,:id_lang,:name_p,:longname_p,:url_p,:resume_p,:content_p,:seo_title_p,:seo_desc_p,:published_p)';
                break;
			case 'catRel':
				$sql = 'INSERT INTO `mc_catalog` (id_product,id_cat,default_c,order_p)
					SELECT :id,:id_cat,:default_c,COUNT(id_catalog) FROM mc_catalog WHERE id_cat IN ('.$params['id_cat'].')';
				break;
            case 'newImg':
                $sql = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img,order_img,default_img) 
						SELECT :id_product,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_catalog_product_img WHERE id_product IN ('.$params['id_product'].')';
                break;
            case 'newImgContent':
                $sql = 'INSERT INTO `mc_catalog_product_img_content`(id_img,id_lang,alt_img,title_img,caption_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img,:caption_img)';
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
				$sql = 'UPDATE mc_catalog_product SET price_p = :price_p, reference_p = :reference_p WHERE id_product = :id_product';
		    	break;
		    case 'content':
				$sql = 'UPDATE mc_catalog_product_content 
					SET 
						name_p = :name_p,
                        longname_p = :longname_p,
                        url_p = :url_p,
                        resume_p = :resume_p,
                        content_p = :content_p,
                        seo_title_p = :seo_title_p, 
                        seo_desc_p = :seo_desc_p,
                        published_p = :published_p
					WHERE id_product = :id_product 
					AND id_lang = :id_lang';
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