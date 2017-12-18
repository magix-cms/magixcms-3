<?php
class frontend_db_webservice
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                //$params = $data;
                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            } elseif ($config['context'] === 'one') {
                if ($config['type'] === 'auth') {
                    //Return current skin
                    $sql = 'SELECT ws.* FROM mc_webservice AS ws LIMIT 1';
                    //$params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }
}