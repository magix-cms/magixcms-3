<?php
class frontend_db_plugins
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';
        if($config['context'] === 'all') {
            switch ($config['type']){
                case 'list':
                    $sql = 'SELECT * FROM mc_plugins';
                    break;
            }
            return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
        } elseif($config['context'] === 'one') {
            switch ($config['type']) {
                case 'installed':
                    $sql = 'SELECT * FROM mc_plugins WHERE name = :name';
                    break;
            }
            return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
        }
    }
}