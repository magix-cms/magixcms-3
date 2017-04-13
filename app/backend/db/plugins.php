<?php
class backend_db_plugins{
    /**
     * @param $config
     * @param bool $data
     * @return mixed
     * @throws Exception
     */
    public function fetch($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'auth') {

            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     * @return mixed|null
     */
    public function fetchAll($config,$data = false){
        $sql = '';
        $params = false;
        if (is_array($config)) {
            if ($config['type'] === 'list') {
                $sql = 'SELECT * FROM mc_plugins';
                //$params = $data;
            }
        }
        return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
    }

    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'list') {
                    $sql = 'SELECT * FROM mc_plugins';
                    //$params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
        }
    }
}
?>