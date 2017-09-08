<?php
class backend_db_files
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'sizes') {
                    //Listing employee
                    $cond = '';
                    if (isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                        $nbc = 0;
                        foreach ($config['search'] as $key => $q) {
                            if ($q != '') {
                                if ($nbc > 0) {
                                    $cond .= 'AND ';
                                } else {
                                    $cond = 'WHERE ';
                                }
                                switch ($key) {
                                    case 'id_config_img':
                                        $cond .= 'conf.' . $key . ' = ' . $q . ' ';
                                        break;
                                    case 'module_img':
                                    case 'attribute_img':
                                        $cond .= "conf." . $key . " LIKE '%" . $q . "%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT conf.* FROM mc_config_img AS conf $cond";
                    //$params = $data;
                }
                elseif ($config['type'] === 'size') {
                    $sql = 'SELECT * FROM mc_config_img WHERE module_img = :module_img AND attribute_img = :attribute_img';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'size') {
                    $sql = 'SELECT * FROM mc_config_img WHERE id_config_img = :id';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     */
    public function insert($config, $data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'newResize') {
                $sql = 'INSERT INTO `mc_config_img`(module_img,attribute_img,width_img,height_img,type_img,resize_img) 
                VALUE(:module_img,:attribute_img,:width_img,:height_img,:type_img,:resize_img)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':module_img'       => $data['module_img'],
                        ':attribute_img'    => $data['attribute_img'],
                        ':width_img'        => $data['width_img'],
                        ':height_img'       => $data['height_img'],
                        ':type_img'         => $data['type_img'],
                        ':resize_img'       => $data['resize_img']
                    )
                );
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
            if ($config['type'] === 'resize') {
                $sql = 'UPDATE mc_config_img SET module_img = :module_img, attribute_img = :attribute_img, 
                width_img=:width_img, height_img=:height_img, type_img=:type_img, resize_img=:resize_img
                WHERE id_config_img = :id_config_img';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_config_img'    => $data['id_config_img'],
                        ':module_img'       => $data['module_img'],
                        ':attribute_img'    => $data['attribute_img'],
                        ':width_img'        => $data['width_img'],
                        ':height_img'       => $data['height_img'],
                        ':type_img'         => $data['type_img'],
                        ':resize_img'       => $data['resize_img']
                    )
                );
            }
        }
    }
    /**
     * @param $config
     * @param bool $data
     */
    public function delete($config,$data = false)
    {
        if (is_array($config)) {
            if($config['type'] === 'delResize'){
                $sql = 'DELETE FROM mc_config_img WHERE id_config_img IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}
?>