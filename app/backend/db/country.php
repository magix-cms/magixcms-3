<?php
class backend_db_country{
    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'countries') {
                    //Listing employee
                    $cond = '';
                    if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                        $nbc = 0;
                        foreach ($config['search'] as $key => $q) {
                            if($q != '') {
                                if($nbc > 0) {
                                    $cond .= 'AND ';
                                } else {
                                    $cond = 'WHERE ';
                                }
                                switch ($key) {
                                    case 'id_country':
                                        $cond .= 'country.'.$key.' = '.$q.' ';
                                        break;
                                    case 'name_country':
                                    case 'iso_country':
                                        $cond .= "country.".$key." LIKE '%".$q."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT country.* FROM mc_country AS country $cond";
                    //$params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'country') {
                    $sql = 'SELECT * FROM mc_lang WHERE id_lang = :id';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>