<?php
class frontend_db_seo
{
    public function fetchData($config, $params = false)
    {
        $sql = '';

        if (is_array($config)) {
            /*if ($config['context'] === 'all') {
                if ($config['type'] === 'seo') {
                    $sql = "SELECT s.*, c.content_seo 
							FROM mc_seo AS s
							JOIN mc_seo_content AS c USING ( id_seo )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.id_lang = :default_lang
							GROUP BY s.id_seo";
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            else*/if ($config['context'] === 'one') {
                if ($config['type'] === 'replace') {
                    //Return current row
                    $sql = 'SELECT * 
							FROM mc_seo 
							JOIN mc_seo_content USING(id_seo)
							LEFT JOIN mc_lang USING(id_lang)
						  	WHERE iso_lang = :iso
						  	AND level_seo = :lvl
						  	AND attribute_seo = :attribute
						  	AND type_seo = :type
							ORDER BY id_seo 
							DESC LIMIT 0,1';
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }
}