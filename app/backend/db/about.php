<?php
class backend_db_about {
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
		$dateFormat = new component_format_date();

		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'info':
					$query = "SELECT a.name_info,a.value_info FROM mc_about AS a";
					break;
				case 'content':
					$query = 'SELECT a.*
						FROM mc_about_data AS a
						JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
					break;
				case 'op':
					$query = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
					break;
				case 'op_content':
					$query = "SELECT * FROM `mc_about_op_content`";
					break;
				case 'languages':
					$query = "SELECT `name_lang` FROM `mc_lang`";
					break;
				case 'iso':
					$query = "SELECT `iso_lang` FROM `mc_lang`";
					break;
				case 'page':
					$query = 'SELECT p.*,c.*,lang.*
							FROM mc_about_page AS p
							JOIN mc_about_page_content AS c USING(id_pages)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_pages = :edit';
					break;
				case 'pages':
					$limit = '';
					if($config['offset']) {
						$limit = ' LIMIT 0, '.$config['offset'];
						if(isset($config['page']) && $config['page'] > 1) {
							$limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
						}
					}

					$query = "SELECT p.id_pages, c.name_pages, c.resume_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
							FROM mc_about_page AS p
								JOIN mc_about_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
							ORDER BY p.order_pages".$limit;

					if(isset($config['search'])) {
						$cond = '';
						if (is_array($config['search']) && !empty($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if ($q !== '') {
									$cond .= 'AND ';
									$p = 'p'.$nbc;
									switch ($key) {
										case 'id_pages':
										case 'menu_pages':
											$cond .= 'p.'.$key.' = :'.$p.' ';
											break;
										case 'published_pages':
											$cond .= 'c.'.$key.' = :'.$p.' ';
											break;
										case 'name_pages':
											$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'parent_pages':
											$cond .= "ca.name_pages"." LIKE CONCAT('%', :".$p.", '%') ";
											break;
										case 'date_register':
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
											break;
									}
									$params[$p] = $q;
									$nbc++;
								}
							}

							$query = "SELECT p.id_pages, c.name_pages, c.resume_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register, ca.name_pages AS parent_pages
									FROM mc_about_page AS p
										JOIN mc_about_page_content AS c USING ( id_pages )
										JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
										LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
										LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages AND ca.id_lang = c.id_lang) 
										WHERE c.id_lang = :default_lang $cond
									ORDER BY p.order_pages".$limit;
						}
					}
					break;
				case 'pagesChild':
					$cond = '';
					if(is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if($q !== '') {
								$cond .= 'AND ';
								$p = 'p'.$nbc;
								switch ($key) {
									case 'id_pages':
									case 'menu_pages':
										$cond .= 'p.'.$key.' = '.$p.' ';
										break;
									case 'name_pages':
										$cond .= "c.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
									case 'date_register':
										$q = $dateFormat->date_to_db_format($q);
										$cond .= "p.".$key." LIKE CONCAT('%', :".$p.", '%') ";
										break;
								}
								$params[$p] = $q;
								$nbc++;
							}
						}
					}

					$query = "SELECT p.id_pages, c.name_pages, c.resume_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
							FROM mc_about_page AS p
								JOIN mc_about_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
								LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages AND ca.id_lang = c.id_lang ) 
								WHERE p.id_parent = :id AND c.id_lang = :default_lang $cond";
					break;
				case 'pagesSelect':
					$query = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
						FROM mc_about_page AS p
							JOIN mc_about_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
							LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
							WHERE c.id_lang = :default_lang
							GROUP BY p.id_pages 
						ORDER BY p.id_pages DESC";
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
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'info':
					$query = "SELECT a.name_info,a.value_info FROM mc_about AS a";
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_about_data` WHERE `id_lang` = :id_lang';
					break;
				case 'contentPage':
					$query = 'SELECT * FROM `mc_about_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
					break;
				case 'root':
					$query = 'SELECT * FROM mc_about_page ORDER BY id_pages DESC LIMIT 0,1';
					break;
				case 'close_txt':
					$query = 'SELECT * FROM mc_about_op_content WHERE id_lang = :id_lang';
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
	 * @return bool
	 */
    public function insert(array $config, array $params = []) {
		if($config['context'] === 'about') {
			switch ($config['type']) {
				case 'content':
					$queries = array(
						array(
							'request' => "SET @lang = :id_lang",
							'params' => array('id_lang' => $params['id_lang'])
						),
						array(
							'request' => "INSERT INTO `mc_about_data` (`id_lang`,`name_info`,`value_info`) VALUES
							(@lang,'desc',:dsc),(@lang,'slogan',:slogan),(@lang,'content',:content),(@lang,'seo_desc',:seo_desc),(@lang,'seo_title',:seo_title)",
							'params' => array(
								'dsc' => $params['desc'],
								'slogan' => $params['slogan'],
								'content' => $params['content'],
								'seo_desc' => $params['seo_desc'],
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
				case 'close_txt':
					$query = 'INSERT INTO `mc_about_op_content`(id_lang,'.$params['column'].') 
							VALUES (:id_lang,:value)';
					unset($params['column']);
					break;
			}
		}
		elseif ($config['context'] === 'page') {
			switch ($config['type']) {
				case 'page':
					$cond = $params['id_parent'] != NULL ? ' IN ('.$params['id_parent'].')' : ' IS NULL';
					$query = "INSERT INTO `mc_about_page`(id_parent,menu_pages,order_pages,date_register) 
						SELECT :id_parent,:menu_pages,COUNT(id_pages),NOW() FROM mc_about_page WHERE id_parent".$cond;
					break;
				case 'content':
					$query = 'INSERT INTO `mc_about_page_content`(id_pages,id_lang,name_pages,url_pages,resume_pages,content_pages,seo_title_pages,seo_desc_pages,published_pages) 
						VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:resume_pages,:content_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';
					break;
			}
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function update(array $config, array $params = []) {
		if($config['context'] === 'about') {
			switch ($config['type']) {
				case 'company':
					$query = "UPDATE `mc_about`
						SET `value_info` = CASE `name_info`
							WHEN 'name' THEN :nme
							WHEN 'type' THEN :tpe
							WHEN 'eshop' THEN :eshop
							WHEN 'tva' THEN :tva
						END
						WHERE `name_info` IN ('name','type','eshop','tva')";
					$params = array(
						'nme' 	=> $params['name'],
						'tpe' 	=> $params['type'],
						'eshop' => $params['eshop'],
						'tva' 	=> $params['tva']
					);
					break;
				case 'contact':
					$query = "UPDATE `mc_about`
						SET `value_info` = CASE `name_info`
							WHEN 'mail' THEN :mail
							WHEN 'click_to_mail' THEN :click_to_mail
							WHEN 'crypt_mail' THEN :crypt_mail
							WHEN 'phone' THEN :phone
							WHEN 'mobile' THEN :mobile
							WHEN 'click_to_call' THEN :click_to_call
							WHEN 'fax' THEN :fax
							WHEN 'adress' THEN :adress
							WHEN 'street' THEN :street
							WHEN 'postcode' THEN :postcode
							WHEN 'city' THEN :city
						END
						WHERE `name_info` IN ('mail','click_to_mail','crypt_mail','phone','mobile','click_to_call','fax','adress','street','postcode','city')";

					$params = array(
						'mail' => $params['contact']['mail'],
						'click_to_mail' => $params['contact']['click_to_mail'],
						'crypt_mail' => $params['contact']['crypt_mail'],
						'phone' => $params['contact']['phone'],
						'mobile' => $params['contact']['mobile'],
						'click_to_call' => $params['contact']['click_to_call'],
						'fax' => $params['contact']['fax'],
						'adress' => $params['contact']['adress']['adress'],
						'street' => $params['contact']['adress']['street'],
						'postcode' => $params['contact']['adress']['postcode'],
						'city' => $params['contact']['adress']['city']
					);
					break;
				case 'refesh_lang':
					$query = "UPDATE `mc_about` 
					SET `value_info` = :languages 
					WHERE `name_info` = 'languages'";
					break;
				case 'socials':
					$query = "UPDATE `mc_about`
				SET `value_info` = CASE `name_info`
					WHEN 'facebook' THEN :facebook
					WHEN 'twitter' THEN :twitter
					WHEN 'youtube' THEN :youtube
					WHEN 'linkedin' THEN :linkedin
					WHEN 'viadeo' THEN :viadeo
					WHEN 'pinterest' THEN :pinterest
					WHEN 'instagram' THEN :instagram
					WHEN 'github' THEN :github
					WHEN 'soundcloud' THEN :soundcloud
					WHEN 'tumblr' THEN :tumblr
					WHEN 'tiktok' THEN :tiktok
				END
				WHERE `name_info` IN ('facebook','twitter','youtube','linkedin','viadeo','pinterest','instagram','github','soundcloud','tumblr','tiktok')";

					$params = $params['socials'];
					break;
				case 'content':
					$query = "UPDATE `mc_about_data`
					SET `value_info` = CASE `name_info`
						WHEN 'desc' THEN :dsc
						WHEN 'slogan' THEN :slogan
						WHEN 'content' THEN :content
						WHEN 'seo_desc' THEN :seo_desc
						WHEN 'seo_title' THEN :seo_title
					END
					WHERE `name_info` IN ('desc','slogan','content','seo_desc','seo_title') AND id_lang = :id_lang";

					$params = array(
						'dsc' => $params['desc'],
						'slogan' => $params['slogan'],
						'content' => $params['content'],
						'seo_title' => $params['seo_title'],
						'seo_desc' => $params['seo_desc'],
						'id_lang' => $params['id_lang']
					);
					break;
				case 'enable_op':
					$query = "UPDATE mc_about 
					SET value_info = :enable_op 
					WHERE name_info = 'openinghours'";
					break;
				case 'openinghours':
					foreach ($params['specifications'] as $day => $opt) {
						$query = "UPDATE `mc_about_op`
								SET `open_day` = :open_day,
								`noon_time` = CASE `open_day`
												WHEN '1' THEN :noon_time
												ELSE `noon_time`
												END,
								`open_time` = CASE `open_day`
												WHEN '1' THEN :open_time
												ELSE `open_time`
												END,
								`close_time` = CASE `open_day`
												WHEN '1' THEN :close_time
												ELSE `close_time`
												END,
								`noon_start` = CASE `open_day`
												WHEN '1' THEN
													CASE `noon_time`
													WHEN '1' THEN :noon_start
													ELSE `noon_start`
													END
												ELSE `noon_start`
												END,
								`noon_end` = CASE `open_day`
												WHEN '1' THEN
													CASE `noon_time`
													WHEN '1' THEN :noon_end
													ELSE `noon_end`
													END
												ELSE `noon_end`
												END
								WHERE `day_abbr` = :cday";

						try {
							component_routing_db::layer()->update($query, array(
								'cday' => $day,
								'open_day' => $opt['open_day'],
								'noon_time' => $opt['noon_time'],
								'open_time' => $opt['open_time'],
								'close_time' => $opt['close_time'],
								'noon_start' => $opt['noon_start'],
								'noon_end' => $opt['noon_end'],
							));
							continue;
						}
						catch (Exception $e) {
							return 'Exception reçue : '.$e->getMessage();
						}
					}
					return true;
					break;
				case 'close_txt':
					$query = "UPDATE mc_about_op_content SET ".$params['column']." = :value WHERE id_content = :id";
					unset($params['column']);
					break;
			}
		}
		elseif ($config['context'] === 'page') {
			switch ($config['type']) {
				case 'page':
					$query = 'UPDATE mc_about_page 
						SET 
							id_parent = :id_parent,
						    menu_pages = :menu_pages
						WHERE id_pages = :id_pages';
					break;
				case 'content':
					$query = 'UPDATE mc_about_page_content 
						SET 
							name_pages = :name_pages,
							url_pages = :url_pages,
							resume_pages = :resume_pages,
							content_pages = :content_pages,
							seo_title_pages = :seo_title_pages,
							seo_desc_pages = :seo_desc_pages, 
							published_pages = :published_pages
						WHERE id_pages = :id_pages 
						AND id_lang = :id_lang';
					break;
				case 'order':
					$query = 'UPDATE mc_about_page 
						SET order_pages = :order_pages
						WHERE id_pages = :id_pages';
					break;
				case 'pageActiveMenu':
					$query = 'UPDATE mc_about_page 
						SET menu_pages = :menu_pages 
						WHERE id_pages IN ('.$params['id_pages'].')';
					$params = array('menu_pages' => $params['menu_pages']);
					break;
			}
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	public function delete(array $config, array $params = []) {
		switch ($config['type']) {
			case 'page':
				$query = 'DELETE FROM `mc_about_page` WHERE `id_pages` IN ('.$params['id'].')';
				$params = array();
				break;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
	}
}