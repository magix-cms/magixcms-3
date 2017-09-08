<?php
class backend_db_catalog{
    /**
     * @param $config
     * @param bool $data
     * @return mixed|null
     */
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if($config['type'] === 'content') {
                    $sql = 'SELECT a.*
                    		FROM mc_catalog_data AS a
                    		JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
                }
                elseif($config['type'] === 'category') {
                    $sql = 'SELECT cat.url_cat, cat.id_cat, cat.id_lang,lang.iso_lang, cat.last_update
                    FROM mc_catalog_cat_content AS cat
                    JOIN mc_lang AS lang ON ( cat.id_lang = lang.id_lang )
                    WHERE cat.published_cat =1 AND cat.id_lang = :id_lang';
                    $params = $data;
                }
                elseif($config['type'] === 'product') {
                    $sql = 'SELECT c.* , cat.url_cat, p.url_p, p.id_lang,lang.iso_lang, p.last_update
                    FROM mc_catalog AS c
                    JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
                    JOIN mc_catalog_product_content AS p ON ( c.id_product = p.id_product )
                    JOIN mc_lang AS lang ON ( p.id_lang = lang.id_lang )
                    WHERE c.default_c =1 AND cat.published_cat =1 AND p.published_p =1 AND p.id_lang = :id_lang';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_catalog_data` WHERE `id_lang` = :id_lang';
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
            if ($config['type'] === 'newContent') {
                $queries = array(
                    array(
                        'request'=>"INSERT INTO `mc_catalog_data` (`id_lang`,`name_info`,`value_info`)
				                    VALUE(:id_lang,'name',:nm)",
                        'params'=>array(':id_lang' => $data['id_lang'],':nm' => $data['name'])
                    ),
                    array(
                        'request'=>"INSERT INTO `mc_catalog_data` (`id_lang`,`name_info`,`value_info`)
				                    VALUE(:id_lang,'content',:content)",
                        'params'=>array(':id_lang' => $data['id_lang'],':content' => $data['content'])
                    )
                );
                component_routing_db::layer()->transaction($queries);
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

                    // Update text (root) Data
                $sql = "UPDATE `mc_catalog_data`
                        SET `value_info` = CASE `name_info`
                            WHEN 'name' THEN :nm
                            WHEN 'content' THEN :content
                        END
                        WHERE `name_info` IN ('name','content') AND id_lang = :id_lang";

                component_routing_db::layer()->update($sql,
                    array(
                        ':nm' 		=> $data['name'],
                        ':content' 	=> $data['content'],
                        ':id_lang' 	=> $data['id_lang']
                    )
                );
            }
        }
    }
}
?>