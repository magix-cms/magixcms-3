<?php
class backend_db_webservice{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                //$params = $data;
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'ws') {
                    //Return current skin
                    $sql = 'SELECT ws.* FROM mc_webservice AS ws LIMIT 1';
                    //$params = $data;
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
            if ($config['type'] === 'newWs') {

                $sql = 'INSERT INTO mc_webservice (key_ws,status_ws) VALUE(:key_ws,:status_ws)';
                component_routing_db::layer()->insert($sql,array(
                    ':key_ws' 		=> $data['key_ws'],
                    ':status_ws' 	=> $data['status_ws']
                ));

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
            if ($config['type'] === 'ws') {
                $sql = 'UPDATE mc_webservice 
            SET key_ws = :key_ws, status_ws = :status_ws 
            WHERE id_ws = :id_ws';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_ws'        => $data['id_ws'],
                        ':key_ws' 		=> $data['key_ws'],
                        ':status_ws' 	=> $data['status_ws']
                    )
                );
            }
        }
    }
}
?>