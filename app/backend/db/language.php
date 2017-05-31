<?php
class backend_db_language{

    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'langs') {
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
                                    case 'id_lang':
                                    case 'default_lang':
                                    case 'active_admin':
                                        $cond .= 'lang.'.$key.' = '.$q.' ';
                                        break;
                                    case 'name_lang':
                                    case 'iso_lang':
                                        $cond .= "lang.".$key." LIKE '%".$q."%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT lang.* FROM mc_lang AS lang $cond";
                    //$params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'lang') {
                    $sql = 'SELECT * FROM mc_lang WHERE id_lang = :id';
                    $params = $data;
                }elseif ($config['type'] === 'count') {
                    $sql = 'SELECT count(id_lang) AS nb FROM mc_lang';
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
            if ($config['type'] === 'lang') {
                $sql = 'UPDATE mc_lang SET iso_lang = :iso_lang, name_lang=:name_lang, 
                default_lang=:default_lang, active_lang=:active_lang WHERE id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'	    => $data['id_lang'],
                        ':iso_lang'	    => $data['iso_lang'],
                        ':name_lang'	=> $data['name_lang'],
                        ':default_lang'	=> $data['default_lang'],
                        ':active_lang'	=> $data['active_lang']
                    )
                );
            }elseif ($config['type'] === 'langActive') {
                $sql = 'UPDATE mc_lang SET active_lang = :active_lang WHERE id_lang IN ('.$data['id_lang'].')';
                component_routing_db::layer()->update($sql,
                    array(
                        ':active_lang' => $data['active_lang']
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
            if ($config['type'] === 'newLang') {
                $sql = 'INSERT INTO mc_lang (iso_lang,name_lang,default_lang,active_lang)
                VALUE (:iso_lang,:name_lang,:default_lang,:active_lang)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':iso_lang'	    => $data['iso_lang'],
                        ':name_lang'	=> $data['name_lang'],
                        ':default_lang'	=> $data['default_lang'],
                        ':active_lang'	=> $data['active_lang']
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
            if($config['type'] === 'delLang'){
                $sql = 'DELETE FROM mc_lang WHERE id_lang IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}
?>