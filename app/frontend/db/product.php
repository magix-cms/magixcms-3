<?php
class frontend_db_product {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;
	
	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []) {
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT p.*,c.*,lang.*
					FROM mc_catalog_product AS p
					JOIN mc_catalog_product_content AS c USING(id_product)
					JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				case 'similar':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT rel.*,p.*,c.name_p, c.resume_p, c.content_p, c.url_p,lang.id_lang,lang.iso_lang,default_lang
						FROM mc_catalog_product_rel AS rel
						JOIN mc_catalog_product AS p ON ( rel.id_product_2 = p.id_product )
						JOIN mc_catalog_product_content AS c ON(p.id_product = c.id_product)
						JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				case 'images':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT img.* FROM mc_catalog_product_img AS img $conditions";
					break;
				case 'images_content':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT c.*,lang.iso_lang
					FROM mc_catalog_product_img_content AS c
					JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) $conditions";
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$query = 'SELECT id_product FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
					break;
				case 'page':
					$query = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
					break;
				case 'category':
					$query = 'SELECT * FROM mc_catalog WHERE `id_product` = :id AND id_cat = :id_cat';
					break;
				case 'img':
					$query = 'SELECT * FROM mc_catalog_product_img WHERE `name_img` = :name_img';
					break;
				case 'lastImgId':
					$query = 'SELECT id_img FROM mc_catalog_product_img ORDER BY id_img DESC LIMIT 0,1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
		switch ($config['type']) {
			case 'newPages':
				$query = 'INSERT INTO `mc_catalog_product`(price_p,reference_p,date_register) 
			VALUES (:price_p,:reference_p,NOW())';
				break;
			case 'newContent':
                $query = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,longname_p,url_p,resume_p,content_p,seo_title_p,seo_desc_p,published_p) 
			  			VALUES (:id_product,:id_lang,:name_p,:longname_p,:url_p,:resume_p,:content_p,:seo_title_p,:seo_desc_p,:published_p)';
                break;
			case 'catRel':
				$query = 'INSERT INTO `mc_catalog` (id_product,id_cat,default_c,order_p)
					SELECT :id,:id_cat,:default_c,COUNT(id_catalog) FROM mc_catalog WHERE id_cat IN ('.$params['id_cat'].')';
				break;
            case 'newImg':
                $query = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img,order_img,default_img) 
						SELECT :id_product,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_catalog_product_img WHERE id_product IN ('.$params['id_product'].')';
                break;
            case 'newImgContent':
                $query = 'INSERT INTO `mc_catalog_product_img_content`(id_img,id_lang,alt_img,title_img,caption_img) 
			  			VALUES (:id_img,:id_lang,:alt_img,:title_img,:caption_img)';
                break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
		    case 'product':
				$query = 'UPDATE mc_catalog_product SET price_p = :price_p, reference_p = :reference_p WHERE id_product = :id_product';
		    	break;
		    case 'content':
				$query = 'UPDATE mc_catalog_product_content 
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
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

    /**
     * @param array $config
     * @param array $params
     * @return bool|string
     */
    public function delete(array $config, array $params = []) {
        switch ($config['type']) {
            case 'delImages':
                $query = 'DELETE FROM `mc_catalog_product_img` WHERE `id_img` IN ('.$params['id'].')';
                $params = array();
                break;
            case 'delImagesAll':
                $query = 'DELETE FROM `mc_catalog_product_img` WHERE id_product = :id';
                break;
			default:
				return false;
        }

        try {
            component_routing_db::layer()->delete($query,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
}