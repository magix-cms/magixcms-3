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
            if($config['context'] === 'all' || $config['context'] === 'return') {
                if($config['type'] === 'content') {
                    $sql = 'SELECT a.*
                    		FROM mc_catalog_data AS a
                    		JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';

                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {

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