<?php
class backend_db_country{
    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'countries') {
                    //Listing employee
                    $cond = '';
                    if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                    	$params = array();
                        $nbc = 0;
                        foreach ($config['search'] as $key => $q) {
                            if($q != '') {
                                if($nbc > 0) {
                                    $cond .= 'AND ';
                                } else {
                                    $cond = 'WHERE ';
                                }
								$params[$key] = $q;
                                switch ($key) {
                                    case 'id_country':
                                        $cond .= 'country.'.$key.' = :'.$key.' ';
                                        break;
                                    case 'name_country':
                                    case 'iso_country':
                                        $cond .= "country.".$key." LIKE '%:".$key."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT country.id_country,country.iso_country,country.name_country 
                    		FROM mc_country AS country $cond"." ORDER BY order_country ASC";
                    //$params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'country') {
                    $sql = 'SELECT * FROM mc_country WHERE id_country = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'count') {
                    $sql = 'SELECT count(id_country) AS nb FROM mc_country';
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
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
            if ($config['type'] === 'country') {
                $sql = 'UPDATE mc_country SET iso_country = :iso_country, name_country=:name_country 
                WHERE id_country = :id_country';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_country'	    => $data['id_country'],
                        ':iso_country'	    => $data['iso_country'],
                        ':name_country'	    => $data['name_country']
                    )
                );
            }elseif ($config['type'] === 'order') {
                $sql = 'UPDATE mc_country SET order_country = :order_country
                WHERE id_country = :id_country';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_country'	    => $data['id_country'],
                        ':order_country'	=> $data['order_country']
                    )
                );
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
            if ($config['type'] === 'newCountry') {
                $sql = 'INSERT INTO mc_country (iso_country,name_country,order_country)
                VALUE (:iso_country,:name_country,:order_country)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':iso_country'	    => $data['iso_country'],
                        ':name_country'	    => $data['name_country'],
                        ':order_country'	=> $data['order_country']
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
            if($config['type'] === 'delCountry'){
                $sql = 'DELETE FROM mc_country WHERE id_country IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}
?>