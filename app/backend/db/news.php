<?php
class backend_db_news
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'page') {
                    $sql = 'SELECT p.* , c.* , lang.* , rel.tags_news
                    FROM mc_news AS p
                    JOIN mc_news_content AS c USING ( id_news )
                    JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                    LEFT OUTER JOIN (
                        SELECT tagrel.id_news, lang.id_lang, GROUP_CONCAT( tag.name_tag ORDER BY tagrel.id_rel SEPARATOR "," ) AS tags_news
                        FROM mc_news_tag AS tag
                        JOIN mc_news_tag_rel AS tagrel USING ( id_tag )
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
                    $sql = 'SELECT tag.id_tag,tag.name_tag
                        FROM mc_news_tag AS tag
                        JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
                        WHERE tag.id_lang = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'sitemap') {
                    $sql = "SELECT p.id_news,p.img_news,c.name_news,c.url_news,c.last_update,c.date_publish,c.published_news,lang.iso_lang
                            FROM mc_news AS p
                            JOIN mc_news_content AS c USING(id_news)
                            JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                            WHERE c.published_news = 1 AND c.id_lang = :id_lang";
                    $params = $data;

                }
                elseif ($config['type'] === 'lastNews'){
                    //### -- Dashboard Data
                    $sql = 'SELECT p.id_news,c.name_news,c.last_update,c.date_publish,c.published_news, p.date_register
							FROM mc_news AS p
							JOIN mc_news_content AS c USING(id_news)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang
							ORDER BY p.id_news DESC
							LIMIT 5';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_news ORDER BY id_news DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'page') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_news WHERE `id_news` = :id_news';
                    $params = $data;
                }
                elseif ($config['type'] === 'content') {

                    $sql = 'SELECT * FROM `mc_news_content` WHERE `id_news` = :id_news AND `id_lang` = :id_lang';
                    $params = $data;

                }
                elseif ($config['type'] === 'tag') {
                    $sql = 'SELECT tag.*, (SELECT id_rel FROM mc_news_tag_rel WHERE id_news = :id_news AND id_tag = tag.id_tag) AS rel_tag
                        FROM mc_news_tag AS tag
                        WHERE tag.id_lang = :id_lang AND tag.name_tag LIKE :name_tag';
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
                $sql = 'UPDATE mc_news_content SET name_news = :name_news, url_news = :url_news, resume_news = :resume_news, content_news=:content_news, date_publish=:date_publish, 
                published_news=:published_news
                WHERE id_news = :id_news AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	       => $data['id_lang'],
                        ':id_news'	       => $data['id_news'],
                        ':name_news'       => $data['name_news'],
                        ':url_news'        => $data['url_news'],
                        ':resume_news'     => $data['resume_news'],
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
    /**
     * @param $config
     * @param bool $data
     */
    public function insert($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'newPages') {

                $sql = 'INSERT INTO `mc_news`(date_register) VALUE (NOW())';
                component_routing_db::layer()->insert($sql,array());

            }elseif ($config['type'] === 'newContent') {

                $sql = 'INSERT INTO `mc_news_content`(id_news,id_lang,name_news,url_news,resume_news,content_news,date_publish,published_news) 
				  VALUES (:id_news,:id_lang,:name_news,:url_news,:resume_news,:content_news,:date_publish,:published_news)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_lang'	       => $data['id_lang'],
                    ':id_news'	       => $data['id_news'],
                    ':name_news'       => $data['name_news'],
                    ':url_news'        => $data['url_news'],
                    ':resume_news'     => $data['resume_news'],
                    ':content_news'    => $data['content_news'],
                    ':date_publish'    => $data['date_publish'],
                    ':published_news'  => $data['published_news']
                ));

            }elseif ($config['type'] === 'newTagComb') {
                $queries = array(
                    array('request'=>'INSERT INTO mc_news_tag (id_lang,name_tag) VALUE (:id_lang,:name_tag)','params'=>array(':id_lang' => $data['id_lang'],':name_tag' => $data['name_tag'])),
                    array('request'=>'SET @tag_id = LAST_INSERT_ID()','params'=>array()),
                    array('request'=>'SET @news_id = :id_news','params'=>array(':id_news'=>$data['id_news'])),
                    array('request'=>'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUE (@news_id,@tag_id)','params'=>array())
                );

                component_routing_db::layer()->transaction($queries);

            }elseif ($config['type'] === 'newTag') {

                $sql = 'INSERT INTO mc_news_tag (id_lang,name_tag) VALUES (:id_lang,:name_tag)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':id_lang'	=> $data['id_lang'],
                        ':name_tag'	=> $data['name_tag']
                    )
                );

            }elseif ($config['type'] === 'newTagRel') {

                $sql = 'INSERT INTO mc_news_tag_rel (id_news,id_tag) VALUES (:id_news,:id_tag)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':id_news'	=> $data['id_news'],
                        ':id_tag'	=> $data['id_tag']
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
            if($config['type'] === 'delPages'){
                $sql = 'DELETE FROM mc_news WHERE id_news IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            } elseif($config['type'] === 'tagRel'){
                $sql = 'DELETE FROM mc_news_tag_rel WHERE id_rel = :id_rel';
                component_routing_db::layer()->delete($sql,array(':id_rel'=>$data['id_rel']));
            }
        }
    }
}