<?php
class backend_db_language{

    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'langs') {
                    $sql = 'SELECT * FROM mc_lang';
                    //$params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'register') {
                    $sql = 'SELECT * FROM mc_lang WHERE name = :id';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }

}
?>