<?php
class backend_db_product{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();
        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'pages') {
					$params = $data;
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
									//$params[$key] = $q;
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
											//$params[$key] = $q;
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
                }
                elseif ($config['type'] === 'page') {
                    $sql = 'SELECT p.*,c.*,lang.*
                        FROM mc_catalog_product AS p
                        JOIN mc_catalog_product_content AS c USING(id_product)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE p.id_product = :edit';
                    $params = $data;
                }
                elseif ($config['type'] === 'images') {
                    $sql = 'SELECT img.*
                        FROM mc_catalog_product_img AS img
                        WHERE img.id_product = :id ORDER BY order_img ASC';
                    $params = $data;
                }
                elseif ($config['type'] === 'imagesAll') {
                    $sql = 'SELECT img.*
                        FROM mc_catalog_product_img AS img';
                }
                elseif ($config['type'] === 'catRel') {
                    $sql = 'SELECT id_product, id_cat, default_c FROM mc_catalog WHERE id_product = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'productRel') {
                    $sql = 'SELECT rel.*,c.name_p
                    FROM mc_catalog_product_rel AS rel
                    JOIN mc_catalog_product_content AS c ON(rel.id_product_2 = c.id_product)
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    WHERE rel.id_product = :id AND c.id_lang = :default_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'imgData') {
                    $sql = 'SELECT img.id_img,img.id_product, img.name_img,c.id_lang,c.alt_img,c.title_img,lang.iso_lang
                        FROM mc_catalog_product_img AS img
                        LEFT JOIN mc_catalog_product_img_content AS c USING(id_img)
                        LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE img.id_img = :edit';
                    $params = $data;
                }
				elseif ($config['type'] === 'lastProducts') {
					//### -- Dashboard Data
					$sql = "SELECT p.id_product, c.name_p, p.date_register
							FROM mc_catalog_product AS p
							JOIN mc_catalog_product_content AS c USING ( id_product )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang
							GROUP BY p.id_product 
							ORDER BY p.id_product DESC
							LIMIT 5";
					$params = $data;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    $sql = 'SELECT id_product FROM mc_catalog_product ORDER BY id_product DESC LIMIT 0,1';
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_catalog_product_content` WHERE `id_product` = :id_product AND `id_lang` = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'page') {
                    $sql = 'SELECT * FROM mc_catalog_product WHERE `id_product` = :id_product';
                    $params = $data;
                }
                elseif ($config['type'] === 'rootImg') {
                    $sql = 'SELECT id_img FROM mc_catalog_product_img WHERE id_product = :id ORDER BY id_img DESC LIMIT 0,1';
                }
                elseif ($config['type'] === 'imgContent') {
                    $sql = 'SELECT * FROM mc_catalog_product_img_content WHERE `id_img` = :id_img AND `id_lang` = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'img') {
                    $sql = 'SELECT * FROM mc_catalog_product_img WHERE `id_img` = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'imgDefault') {
                    $sql = 'SELECT id_img FROM mc_catalog_product_img WHERE id_product = :id AND default_img = 1';
                    $params = $data;
                }
                elseif ($config['type'] === 'catRel') {
                    $sql = 'SELECT * FROM mc_catalog WHERE id_product = :id AND id_cat = :id_cat';
                    $params = $data;
                }
				elseif ($config['type'] === 'lastProductRel') {
					$sql = 'SELECT rel.*,c.name_p
                    		FROM mc_catalog_product_rel AS rel
                    		JOIN mc_catalog_product_content AS c ON(rel.id_product_2 = c.id_product)
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    		WHERE rel.id_product = :id AND c.id_lang = :default_lang
                    		ORDER BY rel.id_rel DESC LIMIT 0,1';
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
			if ($config['type'] === 'newPages') {
				$sql = 'INSERT INTO `mc_catalog_product`(price_p,reference_p,date_register) 
                VALUES (:price_p,:reference_p,NOW())';
				component_routing_db::layer()->insert($sql,array(
					':price_p'	        => $data['price_p'],
					':reference_p'	    => $data['reference_p']
				));
			}
			elseif ($config['type'] === 'newContent') {
				$sql = 'INSERT INTO `mc_catalog_product_content`(id_product,id_lang,name_p,url_p,resume_p,content_p,published_p) 
				  VALUES (:id_product,:id_lang,:name_p,:url_p,:resume_p,:content_p,:published_p)';

				component_routing_db::layer()->insert($sql,array(
					':id_lang'	    => $data['id_lang'],
					':id_product'	=> $data['id_product'],
					':name_p'       => $data['name_p'],
					':url_p'        => $data['url_p'],
					':resume_p'     => $data['resume_p'],
					':content_p'    => $data['content_p'],
					':published_p'  => $data['published_p']
				));
			}
			elseif ($config['type'] === 'newImg') {
				$sql = 'INSERT INTO `mc_catalog_product_img`(id_product,name_img,order_img,default_img) 
						SELECT :id_product,:name_img,COUNT(id_img),IF(COUNT(id_img) = 0,1,0) FROM mc_catalog_product_img WHERE id_product IN ('.$data['id_product'].')';
				component_routing_db::layer()->insert($sql,array(
					':id_product'	    => $data['id_product'],
					':name_img'	        => $data['name_img']
				));
			}
            elseif ($config['type'] === 'newImgContent') {
                $sql = 'INSERT INTO `mc_catalog_product_img_content`(id_img,id_lang,alt_img,title_img) 
				  VALUES (:id_img,:id_lang,:alt_img,:title_img)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_lang'	    => $data['id_lang'],
                    ':id_img'	    => $data['id_img'],
                    ':alt_img'      => $data['alt_img'],
                    ':title_img'    => $data['title_img']
                ));
            }
			elseif ($config['type'] === 'catRel') {
				$sql = 'INSERT INTO `mc_catalog` (id_product,id_cat,default_c,order_p)
						SELECT :id,:id_cat,:default_c,COUNT(id_catalog) FROM mc_catalog WHERE id_cat IN ('.$data[':id_cat'].')';

				component_routing_db::layer()->insert($sql,$data);
			}
			elseif ($config['type'] === 'productRel') {
                $sql = 'INSERT INTO `mc_catalog_product_rel` (id_product,id_product_2)
						VALUES (:id_product,:id_product_2)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_product'	    => $data['id_product'],
                    ':id_product_2'	    => $data['id_product_2']
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
            }
            elseif ($config['type'] === 'content') {
                $sql = 'UPDATE mc_catalog_product_content 
						SET 
							name_p = :name_p,
							url_p = :url_p,
							resume_p = :resume_p,
							content_p = :content_p,
							published_p = :published_p
							WHERE id_product = :id_product 
                		AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	    => $data['id_lang'],
                        ':id_product'	=> $data['id_product'],
                        ':name_p'       => $data['name_p'],
                        ':url_p'        => $data['url_p'],
                        ':resume_p'     => $data['resume_p'],
                        ':content_p'    => $data['content_p'],
                        ':published_p'  => $data['published_p']
                    )
                );
            }

            elseif ($config['type'] === 'imgContent') {
                $sql = 'UPDATE mc_catalog_product_img_content SET alt_img = :alt_img, title_img = :title_img
                WHERE id_img = :id_img AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	    => $data['id_lang'],
                        ':id_img'	    => $data['id_img'],
                        ':alt_img'      => $data['alt_img'],
                        ':title_img'    => $data['title_img']
                    )
                );
            }

            elseif ($config['type'] === 'catRel') {
                $sql = 'UPDATE mc_catalog
                		SET default_c = CASE id_cat
							WHEN :id_cat THEN 1
							ELSE 0
						END
						WHERE id_product = :id';

                component_routing_db::layer()->update($sql, $data);
            }
            elseif ($config['type'] === 'imageDefault') {
                $sql = 'UPDATE mc_catalog_product_img
                		SET default_img = CASE id_img
							WHEN :id_img THEN 1
							ELSE 0
						END
						WHERE id_product = :id';

                component_routing_db::layer()->update($sql, $data);
            }
            elseif ($config['type'] === 'firstImageDefault') {
                $sql = 'UPDATE mc_catalog_product_img
                		SET default_img = 1
                		WHERE id_product = :id 
						ORDER BY order_img ASC 
						LIMIT 1';

                component_routing_db::layer()->update($sql, $data);
            }
			elseif ($config['type'] === 'order') {
				$sql = 'UPDATE mc_catalog_product_img SET order_img = :order_img
                		WHERE id_img = :id_img';
				component_routing_db::layer()->update($sql,
					array(
						':id_img'	    => $data['id_img'],
						':order_img'	=> $data['order_img']
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
            if($config['type'] === 'delPages') {
                $sql = 'DELETE FROM mc_catalog_product WHERE id_product IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
            elseif($config['type'] === 'delImages') {
				$sql = 'DELETE FROM mc_catalog_product_img WHERE id_img IN ('.$data['id'].')';
				component_routing_db::layer()->delete($sql,array());
			}
            elseif($config['type'] === 'catRel') {
				$sql = 'DELETE FROM mc_catalog WHERE id_product = :id';
				component_routing_db::layer()->delete($sql,$data);
			}
            elseif($config['type'] === 'oldCatRel') {
				$sql = 'DELETE FROM mc_catalog WHERE id_product = '.$data[':id'].' AND id_cat NOT IN ('.$data[':id_cat'].')';
				component_routing_db::layer()->delete($sql,array());
			}
            elseif($config['type'] === 'productRel') {
                $sql = 'DELETE FROM mc_catalog_product_rel WHERE id_rel IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}