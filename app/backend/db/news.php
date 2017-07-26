<?php
class backend_db_news
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'page') {
                    $sql = 'SELECT p.* , c.* , lang.* , rel.tags_news
                    FROM mc_news AS p
                    JOIN mc_news_content AS c USING ( id_news )
                    JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                    LEFT OUTER JOIN (
                        SELECT tagrel.id_news, lang.id_lang, GROUP_CONCAT( tag.name_tag
                        ORDER BY tagrel.id_rel
                        SEPARATOR "," ) AS tags_news
                        FROM mc_news_tag AS tag
                        JOIN mc_news_tag_rel AS tagrel
                        USING ( id_tag )
                        JOIN mc_lang AS lang ON ( tag.id_lang = lang.id_lang )
                        GROUP BY tagrel.id_news, lang.id_lang
                        )rel ON ( rel.id_news = p.id_news AND rel.id_lang = c.id_lang)
                    WHERE p.id_news = :edit';
                    $params = $data;
                }
                elseif ($config['type'] === 'news') {
					$sql = 'SELECT c.id_news,c.name_news,c.content_news,p.img_news,c.last_update,c.date_publish,c.published_news
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang';

					if(isset($config['search'])) {
						$cond = '';
						$config['search'] = array_filter($config['search']);
						if(is_array($config['search']) && !empty($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if($q != '') {
									$cond .= 'AND ';
									switch ($key) {
										case 'id_news':
										case 'published_news':
											$cond .= 'c.'.$key.' = '.$q.' ';
											break;
										case 'name_news':
											$cond .= "c.".$key." LIKE '%".$q."%' ";
											break;
										case 'last_update':
										case 'date_publish':
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "c.".$key." LIKE '%".$q."%' ";
											break;
									}
									$nbc++;
								}
							}

							$sql = "SELECT c.id_news,c.name_news,c.content_news,p.img_news,c.last_update,c.date_publish,c.published_news
									FROM mc_news AS p
									JOIN mc_news_content AS c USING(id_news)
									JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
									WHERE c.id_lang = :default_lang $cond";
						}
					}

					$params = $data;
                }
                elseif ($config['type'] === 'img') {
                    $sql = 'SELECT p.id_news, p.img_news
                        FROM mc_news AS p WHERE p.img_news IS NOT NULL';
                }
                elseif ($config['type'] === 'tags') {
                    $sql = 'SELECT tag.name_tag
                        FROM mc_news_tag AS tag
                        JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
                        WHERE tag.id_lang = :id_lang';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;

            }elseif ($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'page') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_news WHERE `id_news` = :id_news';
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
            if ($config['type'] === 'content') {
                $sql = 'UPDATE mc_news_content SET name_news = :name_news, url_news = :url_news, content_news=:content_news, date_publish=:date_publish, 
                published_news=:published_news
                WHERE id_news = :id_news AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	       => $data['id_lang'],
                        ':id_news'	       => $data['id_news'],
                        ':name_news'       => $data['name_news'],
                        ':url_news'        => $data['url_news'],
                        ':content_news'    => $data['content_news'],
                        ':date_publish'    => $data['date_publish'],
                        ':published_news'  => $data['published_news']
                    )
                );
            }elseif ($config['type'] === 'img') {
                $sql = 'UPDATE mc_news SET img_news = :img_news
                WHERE id_news = :id_news';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_news'	      => $data['id_news'],
                        ':img_news'       => $data['img_news']
                    )
                );
            }elseif ($config['type'] === 'order') {
                $sql = 'UPDATE mc_news SET order_news = :order_news
                WHERE id_news = :id_news';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_news'	    => $data['id_news'],
                        ':order_news'	=> $data['order_news']
                    )
                );
            }
        }
    }
}