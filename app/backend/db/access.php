<?php
class backend_db_access{
    /**
     * @param $config
     * @param bool $data
     * @return mixed
     * @throws Exception
     */
    public function fetchData($config,$data = false){
        if(is_array($config)) {
			$sql = '';
			$params = false;

            if($config['context'] === 'all') {
                if ($config['type'] === 'roles') {
                    //Return role list
                    $sql = 'SELECT * FROM mc_admin_role_user
                    		ORDER BY id_role DESC';
                }
                elseif($config['type'] === 'access'){
                    $sql = 'SELECT ace.*,module.class_name,module.name
							FROM mc_admin_access AS ace
							JOIN mc_module as module ON(ace.id_module = module.id_module)
							WHERE ace.id_role = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'module') {
                    //Return role list
                    $sql = 'SELECT * FROM mc_module';
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'role') {
                    //Return role list
                    $sql = 'SELECT * FROM mc_admin_role_user
                    		WHERE id_role = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'lastRole') {
                    //Return role list
                    $sql = 'SELECT * FROM mc_admin_role_user
                    		ORDER BY id_role DESC LIMIT 0,1';
                }
                elseif ($config['type'] === 'lastAccess') {

                    $sql = "SELECT ace.*,module.class_name,module.name
							FROM mc_admin_access as ace
							JOIN mc_module as module ON(ace.id_module = module.id_module)
							WHERE ace.id_role = :id
							ORDER BY ace.id_access DESC LIMIT 0,1";
                    $params = $data;
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
            if ($config['type'] === 'newRole') {
                $sql = 'INSERT INTO mc_admin_role_user (role_name)
                VALUE (:role_name)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':role_name'	=> $data['role_name']
                    )
                );
            }elseif ($config['type'] === 'newAccess') {
                $sql = 'INSERT INTO mc_admin_access (id_role,id_module,view,append,edit,del,action)
                VALUE (:id_role,:id_module,:view,:append,:edit,:del,:action)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':id_role'      => $data['id_role'],
                        ':id_module'    => $data['id_module'],
                        ':view'         => $data['view'],
                        ':append'       => $data['append'],
                        ':edit'         => $data['edit_access'],
                        ':del'          => $data['del'],
                        ':action'       => $data['action_access']
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
            if ($config['type'] === 'role') {

                $sql = 'UPDATE mc_admin_role_user 
                SET role_name=:role_name
		        WHERE id_role = :id_role';

                component_routing_db::layer()->update($sql,array(
                    ':id_role' => $data['id_role'],
                    ':role_name' => $data['role_name']
                ));

            }elseif ($config['type'] === 'access') {

                $sql = 'UPDATE mc_admin_access
								SET view = :view,
									append = :append,
									edit = :edit,
									del = :del,
									action= :action
								WHERE id_access = :id_access';
                component_routing_db::layer()->update($sql,array(
                        ':id_access'    => $data['id_access'],
                        ':view'  => $data['view'],
                        ':append'   => $data['append'],
                        ':edit'  => $data['edit_access'],
                        ':del'=> $data['del'],
                        ':action'=> $data['action_access']
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
            if($config['context'] === 'role'){
                if($config['type'] === 'delRole') {
                    $queries = array(
                        array('request'=>'DELETE FROM mc_admin_access WHERE id_role IN('.$data['id'].')','params'=>array()),
                        array('request'=>'DELETE FROM mc_admin_role_user WHERE id_role IN('.$data['id'].')','params'=>array()),
                        array('request'=>'UPDATE mc_admin_access_rel SET id_role=1 WHERE id_role IN('.$data['id'].')','params'=>array())
                    );
                    component_routing_db::layer()->transaction($queries);
                }
            }
        }
    }
}