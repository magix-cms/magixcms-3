<?php
class backend_db_home{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'pages') {
                    $sql = 'SELECT h.*,c.*
                    FROM mc_home_page AS h
                    JOIN mc_home_page_content AS c USING(id_page)
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)';
                }
                //$params = $data;
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'root') {
                    //Return current skin
                    $sql = 'SELECT * FROM mc_home_page ORDER BY id_page DESC LIMIT 0,1';
                    //$params = $data;
                }elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_home_page_content` WHERE `id_page` = :id_page AND `id_lang` = :id_lang';
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>