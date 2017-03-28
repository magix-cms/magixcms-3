<?php
class component_collections_plugins{

    public function fetchAll(){
        $sql = 'SELECT *
    	FROM mc_plugins';
        return component_routing_db::layer()->fetchAll($sql);
    }
    public function fetch($data){
        if(is_array($data)) {
            if (array_key_exists('context', $data)) {
                $context = $data['context'];
            }
            if($context === 'check') {
                $sql = 'SELECT *
    	        FROM mc_plugins WHERE name = :name';
                return component_routing_db::layer()->fetch($sql, array(
                    ':name' => $data['name']
                ));
            }
        }
    }
}
?>