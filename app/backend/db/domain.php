<?php
class backend_db_domain
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'domain') {
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
                                    case 'id_domain':
                                        $cond .= 'd.' . $key . ' = ' . $q . ' ';
                                        break;
                                    case 'url_domain':
                                        $cond .= "d." . $key . " LIKE '%" . $q . "%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT d.* 
                    FROM mc_domain AS d $cond";
                    //$params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'domain') {
                    $sql = 'SELECT * FROM mc_domain WHERE id_domain = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'count') {
                    $sql = 'SELECT count(id_domain) AS nb FROM mc_domain';
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
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
            if ($config['type'] === 'newDomain') {
                $sql = 'INSERT INTO mc_domain (url_domain,default_domain)
                VALUE (:url_domain,:default_domain)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':url_domain'	    => $data['url_domain'],
                        ':default_domain'	=> $data['default_domain']
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
            if ($config['type'] === 'domain') {
                $sql = 'UPDATE mc_domain SET url_domain = :url_domain, 
                default_domain=:default_domain 
                WHERE id_domain = :id_domain';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_domain'	    => $data['id_domain'],
                        ':url_domain'	    => $data['url_domain'],
                        ':default_domain'	=> $data['default_domain']
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
            if($config['type'] === 'delDomain'){
                $sql = 'DELETE FROM mc_domain WHERE id_domain IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}