<?php
class backend_db_files{
    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'sizes') {
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
                                    case 'id_config_img':
                                        $cond .= 'conf.'.$key.' = '.$q.' ';
                                        break;
                                    case 'module_img':
                                    case 'attribute_img':
                                        $cond .= "conf.".$key." LIKE '%".$q."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT conf.* FROM mc_config_img AS conf $cond";
                    //$params = $data;
                }elseif ($config['type'] === 'size') {
                    $sql = 'SELECT * FROM mc_config_img WHERE module_img = :module_img AND attribute_img = :attribute_img';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'count') {
                    $sql = 'SELECT count(id_lang) AS nb FROM mc_lang';
                }
                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>