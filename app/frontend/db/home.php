<?php
class frontend_db_home
{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all' || $config['context'] === 'return') {

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'page') {
                    //Return current skin
                    $sql = 'SELECT
                    h.*,c.*,lang.iso_lang
                    FROM mc_home_page AS h
                    JOIN mc_home_page_content AS c ON(h.id_page = c.id_page) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    WHERE lang.iso_lang = :iso AND c.published = 1';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>