<?php
class backend_db_about
{
	/**
	 * @param $config
	 * @param bool $data
	 * @return mixed|null
	 */
    public function fetchData($config,$data = false)
	{
        $sql = '';
        $params = false;
		$dateFormat = new component_format_date();

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'info') {
                    $sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT a.*
                    		FROM mc_about_data AS a
                    		JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
                }
                elseif ($config['type'] === 'op') {
                    $sql = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
                }
                elseif ($config['type'] === 'languages') {
                	$sql = "SELECT `name_lang` FROM `mc_lang`";
				}
                elseif ($config['type'] === 'iso') {
                	$sql = "SELECT `iso_lang` FROM `mc_lang`";
				}
				elseif ($config['type'] === 'page') {
					$sql = 'SELECT p.*,c.*,lang.*
                        FROM mc_about_page AS p
                        JOIN mc_about_page_content AS c USING(id_pages)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE p.id_pages = :edit';
					$params = $data;
				}
				elseif ($config['type'] === 'pages') {
					$sql = "SELECT p.id_pages, c.name_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register
								FROM mc_about_page AS p
									JOIN mc_about_page_content AS c USING ( id_pages )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang AND p.id_parent IS NULL 
									GROUP BY p.id_pages 
								ORDER BY p.order_pages";
					if(isset($config['search'])) {
						$cond = '';
						$config['search'] = array_filter($config['search']);
						if (is_array($config['search']) && !empty($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if ($q != '') {
									/*if($nbc > 0) {
										$cond .= 'AND ';
									} else {
										$cond = 'WHERE ';
									}*/
									$cond .= 'AND ';
									switch ($key) {
										case 'id_pages':
										case 'published_pages':
											$cond .= 'c.' . $key . ' = ' . $q . ' ';
											break;
										case 'name_pages':
											$cond .= "c." . $key . " LIKE '%" . $q . "%' ";
											break;
										case 'parent_pages':
											$cond .= "ca.name_pages" . " LIKE '%" . $q . "%' ";
											break;
										case 'menu_pages':
											$cond .= 'p.' . $key . ' = ' . $q . ' ';
											break;
										case 'date_register':
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "p." . $key . " LIKE '%" . $q . "%' ";
											break;
									}
									$nbc++;
								}
							}

							$sql = "SELECT p.id_pages, c.name_pages, c.content_pages, c.seo_title_pages, c.seo_desc_pages, p.menu_pages, p.date_register, ca.name_pages AS parent_pages
								FROM mc_about_page AS p
									JOIN mc_about_page_content AS c USING ( id_pages )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
									LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
									WHERE c.id_lang = :default_lang $cond
									GROUP BY p.id_pages 
								ORDER BY p.order_pages";
						}
					}
					$params = $data;
				}
				elseif ($config['type'] === 'pagesSelect') {
					$sql = "SELECT p.id_parent,p.id_pages, c.name_pages , ca.name_pages AS parent_pages
							FROM mc_about_page AS p
								JOIN mc_about_page_content AS c USING ( id_pages )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
								LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
								WHERE c.id_lang = :default_lang
								GROUP BY p.id_pages 
							ORDER BY p.id_pages DESC";
					$params = $data;
				}
				elseif ($config['type'] === 'pagesChild') {
					$cond = '';
					if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						foreach ($config['search'] as $key => $q) {
							if($q != '') {
								$cond .= 'AND ';
								switch ($key) {
									case 'id_pages':
										$cond .= 'c.'.$key.' = '.$q.' ';
										break;
									case 'name_pages':
										$cond .= "c.".$key." LIKE '%".$q."%' ";
										break;
									case 'menu_pages':
										$cond .= 'p.'.$key.' = '.$q.' ';
										break;
									case 'date_register':
										$q = $dateFormat->date_to_db_format($q);
										$cond .= "p.".$key." LIKE '%".$q."%' ";
										break;
								}
								$nbc++;
							}
						}
					}
					$sql = "SELECT p.id_pages, c.name_pages, p.menu_pages, p.date_register
                    FROM mc_about_page AS p
                        JOIN mc_about_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        WHERE p.id_parent = :id $cond
                        GROUP BY p.id_pages 
                    ORDER BY p.order_pages";

					$params = $data;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'info') {
                    $sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_about_data` WHERE `id_lang` = :id_lang';
                    $params = $data;
                }
				elseif ($config['type'] === 'contentPage') {
					$sql = 'SELECT * FROM `mc_about_page_content` WHERE `id_pages` = :id_pages AND `id_lang` = :id_lang';
					$params = $data;
				}
                elseif ($config['type'] === 'root') {
					$sql = 'SELECT * FROM mc_about_page ORDER BY id_pages DESC LIMIT 0,1';
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
			$sql = '';
			$params = $data;

        	if($config['context'] === 'about') {
				if ($config['type'] === 'content') {
					$queries = array(
						array(
							'request' => "SET @lang = :id_lang",
							'params' => array('id_lang' => $data['id_lang'])
						),
						array(
							'request' => "INSERT INTO `mc_about_data` (`id_lang`,`name_info`,`value_info`) VALUES
								(@lang,'desc',:dsc),(@lang,'slogan',:slogan),(@lang,'content',:content)",
							'params' => array(
								'dsc' => $data['desc'],
								'slogan' => $data['slogan'],
								'content' => $data['content']
							)
						),
					);
					component_routing_db::layer()->transaction($queries);
				}
			}
			elseif ($config['context'] === 'page') {
				if ($config['type'] === 'page') {
					$cond = $data['id_parent'] != NULL ? 'IN ('.$data['id_parent'].')' : 'IS NULL';
					$sql = "INSERT INTO `mc_about_page`(id_parent,order_pages,date_register) 
							SELECT :id_parent,COUNT(id_pages),NOW() FROM mc_about_page WHERE id_parent ".$cond;
				}
				elseif ($config['type'] === 'content') {
					$sql = 'INSERT INTO `mc_about_page_content`(id_pages,id_lang,name_pages,url_pages,resume_pages,content_pages,seo_title_pages,seo_desc_pages,published_pages) 
				  			VALUES (:id_pages,:id_lang,:name_pages,:url_pages,:resume_pages,:content_pages,:seo_title_pages,:seo_desc_pages,:published_pages)';
				}

				if($sql && $params) component_routing_db::layer()->insert($sql,$params);
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
			$params = false;

			if($config['context'] === 'about') {
				if ($config['type'] === 'company') {
					// Update company Data
					$sql = "UPDATE `mc_about`
						SET `value_info` = CASE `name_info`
							WHEN 'name' THEN :nme
							WHEN 'type' THEN :tpe
							WHEN 'eshop' THEN :eshop
							WHEN 'tva' THEN :tva
						END
						WHERE `name_info` IN ('name','type','eshop','tva')";
					$params = array(
						':nme' 		=> $data['name'],
						':tpe' 		=> $data['type'],
						':eshop' 	=> $data['eshop'],
						':tva' 		=> $data['tva']
					);
				}
				elseif ($config['type'] === 'contact') {
					// Update contact Data
					$sql = "UPDATE `mc_about`
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
						':mail' 			=> $data['contact']['mail'],
						':click_to_mail'	=> $data['contact']['click_to_mail'],
						':crypt_mail'		=> $data['contact']['crypt_mail'],
						':phone' 			=> $data['contact']['phone'],
						':mobile' 			=> $data['contact']['mobile'],
						':click_to_call'	=> $data['contact']['click_to_call'],
						':fax' 				=> $data['contact']['fax'],
						':adress' 			=> $data['contact']['adress']['adress'],
						':street' 			=> $data['contact']['adress']['street'],
						':postcode' 		=> $data['contact']['adress']['postcode'],
						':city' 			=> $data['contact']['adress']['city']
					);

				}
				elseif ($config['type'] === 'refesh_lang') {
					// Update contact languages
					$sql = "UPDATE `mc_about` 
						SET `value_info` = :languages 
						WHERE `name_info` = 'languages'";

					$params = array(
						':languages' => $data['languages']
					);

				}
				elseif ($config['type'] === 'socials') {
					// Update socials Data

					$sql = "UPDATE `mc_about`
					SET `value_info` = CASE `name_info`
						WHEN 'facebook' THEN :facebook
						WHEN 'twitter' THEN :twitter
						WHEN 'google' THEN :google
						WHEN 'linkedin' THEN :linkedin
						WHEN 'viadeo' THEN :viadeo
						WHEN 'pinterest' THEN :pinterest
						WHEN 'instagram' THEN :instagram
						WHEN 'github' THEN :github
						WHEN 'soundcloud' THEN :soundcloud
					END
					WHERE `name_info` IN ('facebook','twitter','google','linkedin','viadeo','pinterest','instagram','github','soundcloud')";

					$params = $data['socials'];

				}
				elseif ($config['type'] === 'content') {
					// Update text (root) Data
					$sql = "UPDATE `mc_about_data`
						SET `value_info` = CASE `name_info`
							WHEN 'desc' THEN :dsc
							WHEN 'slogan' THEN :slogan
							WHEN 'content' THEN :content
						END
						WHERE `name_info` IN ('desc','slogan','content') AND id_lang = :id_lang";

					$params = array(
						':dsc' 		=> $data['desc'],
						':slogan' 	=> $data['slogan'],
						':content' 	=> $data['content'],
						':id_lang' 	=> $data['id_lang']
					);
				}
				elseif ($config['type'] === 'enable_op') {
					$sql = "UPDATE mc_about 
						SET value_info = :enable 
						WHERE name_info = 'openinghours'";

					$params = array(
						':enable' => $data['enable_op']
					);

				}
				elseif ($config['type'] === 'openinghours') {
					foreach ($data['specifications'] as $day => $opt) {
						$sql = "UPDATE `mc_about_op`
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

						component_routing_db::layer()->update($sql, array(
							':cday'			=> $day,
							':open_day' 	=> $opt['open_day'],
							':noon_time' 	=> $opt['noon_time'],
							':open_time' 	=> $opt['open_time'],
							':close_time' 	=> $opt['close_time'],
							':noon_start' 	=> $opt['noon_start'],
							':noon_end' 	=> $opt['noon_end'],
						));
					}
				}
			}
			elseif ($config['context'] === 'page') {
				if ($config['type'] === 'page') {
					$sql = 'UPDATE mc_about_page 
							SET 
								id_parent = :id_parent
							WHERE id_pages = :id_pages';
					$params = $data;
				}
				elseif ($config['type'] === 'content') {
					$sql = 'UPDATE mc_about_page_content 
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
					$params = $data;
				}
				elseif ($config['type'] === 'order') {
					$sql = 'UPDATE mc_about_page 
							SET order_pages = :order_pages
							WHERE id_pages = :id_pages';
					$params = $data;
				}
			}

            if($sql && $params) component_routing_db::layer()->update($sql,$params);
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
			case 'page':
				$sql = 'DELETE FROM `mc_about_page` 
						WHERE `id_pages` IN ('.$params['id'].')';
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