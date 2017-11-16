<?php
class frontend_db_news
{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'langs') {
                    $sql = 'SELECT p.*,c.*,lang.iso_lang,lang.default_lang
                    		FROM mc_news AS p
                    		JOIN mc_news_content AS c ON(c.id_news = p.id_news)
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    		WHERE p.id_news = :id AND c.published_news = 1';
                    $params = $data;
                }
                elseif ($config['type'] === 'pages') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT p.*,c.*,lang.iso_lang,lang.default_lang
                    		FROM mc_news AS p
                    		JOIN mc_news_content AS c ON(c.id_news = p.id_news)
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    $conditions";

                    $params = $data;
                    //WHERE lang.iso_lang = :iso AND c.published_pages = 1
                }
                elseif ($config['type'] === 'tagsRel') {
                    $sql = 'SELECT tag.id_tag,tag.name_tag,lang.iso_lang
                            FROM mc_news_tag AS tag
                            JOIN mc_news_tag_rel AS tagrel USING ( id_tag )
                            JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang)
                            WHERE tagrel.id_news = :id AND lang.iso_lang = :iso';
                    $params = $data;
                }
                elseif ($config['type'] === 'tags') {
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                    $sql = "SELECT tag.id_tag,tag.name_tag,lang.iso_lang
                            FROM mc_news_tag AS tag
                            JOIN mc_lang AS lang ON(tag.id_lang = lang.id_lang) 
                            $conditions";
                    $params = $data;
                }
                elseif ($config['type'] === 'archives') {
					$sql = "SELECT GROUP_CONCAT(DISTINCT MONTH(`date_publish`)) AS mths, YEAR(`date_publish`) AS yr
							FROM mc_news AS news
							JOIN mc_news_content AS c USING(id_news)
                    		JOIN mc_lang AS lang USING(id_lang)
							WHERE c.published_news = 1
							AND lang.iso_lang = :iso
							GROUP BY YEAR(date_publish)
							ORDER BY date_publish DESC";
					$params = $data;
				}
                elseif ($config['type'] === 'ws') {
                    //Return current row
                    $sql = 'SELECT p.img_news,c.*,lang.iso_lang,lang.default_lang
                    		FROM mc_news AS p
                    		JOIN mc_news_content AS c ON(c.id_news = p.id_news)
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
                    		WHERE p.id_news = :id';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'page') {
                    //Return current row
                    $sql = 'SELECT p.img_news,c.*,lang.iso_lang
                    		FROM mc_news AS p
                    		JOIN mc_news_content AS c ON(c.id_news = p.id_news)
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)  
                    		WHERE p.id_news = :id AND lang.iso_lang = :iso AND c.published_news = 1';
                    $params = $data;
                }
				elseif ($config['type'] === 'nb_archives') {
					$sql = "SELECT COUNT(`id_news`) AS nbr
							FROM mc_news AS news
							JOIN mc_news_content AS c USING(id_news)
                    		JOIN mc_lang AS lang USING(id_lang)
							WHERE c.published_news = 1
							AND lang.iso_lang = :iso
							AND YEAR(date_publish) = :yr
							AND MONTH(date_publish) = :mth";
					$params = $data;
				}
				elseif ($config['type'] === 'tag') {
					$sql = "SELECT id_tag as id, name_tag as name FROM mc_news_tag WHERE id_tag = :id";
					$params = $data;
				}
                // Web Service
                elseif ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_news ORDER BY id_news DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'image') {
                    //Return image
                    $sql = 'SELECT img_news FROM mc_news WHERE `id_news` = :id_news';
                    $params = $data;
                }
                elseif ($config['type'] === 'content') {

                    $sql = 'SELECT * FROM `mc_news_content` WHERE `id_news` = :id_news AND `id_lang` = :id_lang';
                    $params = $data;

                }
                elseif ($config['type'] === 'tag_ws') {
                    $sql = 'SELECT tag.*, (SELECT id_rel FROM mc_news_tag_rel WHERE id_news = :id_news AND id_tag = tag.id_tag) AS rel_tag
                        FROM mc_news_tag AS tag
                        WHERE tag.id_lang = :id_lang AND tag.name_tag LIKE :name_tag';
                    $params = $data;
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
            if ($config['type'] === 'page') {

                $sql = 'INSERT INTO `mc_news`(date_register) VALUE (NOW())';
                component_routing_db::layer()->insert($sql,array());

            }elseif ($config['type'] === 'content') {

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
?>