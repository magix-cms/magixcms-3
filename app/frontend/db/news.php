<?php
class frontend_db_news
{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'langs') {
                    $sql = 'SELECT p.*,c.*,lang.iso_lang
                    		FROM mc_news AS p
                    		JOIN mc_news_content AS c ON(c.id_news = p.id_news)
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    		WHERE p.id_news = :id AND c.published_news = 1';
                    $params = $data;
                }
                elseif ($config['type'] === 'pages') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT p.*,c.*,lang.iso_lang
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

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>