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
            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'register') {
                    $sql = 'SELECT * FROM mc_plugins WHERE name = :id';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
    public function insert($config,$data = false){
        if (is_array($config)) {
            if ($config['type'] === 'register') {
                $sql = 'INSERT INTO mc_plugins (name,version)
                VALUE (:name,:version)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':name' => $data['name'],
                        ':version'  => $data['version']
                    )
                );
            }
        }
    }
    public function update($config,$data = false){
        if (is_array($config)) {
            if ($config['type'] === 'version') {
                $sql = 'UPDATE mc_plugins SET version = :version WHERE name = :name';
                component_routing_db::layer()->update($sql,
                    array(
                        ':name'     => $data['name'],
                        ':version'  => $data['version']
                    )
                );
            }
        }
    }
}
?>