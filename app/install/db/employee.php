<?php
class install_db_employee
{
    /**
     * @param $config
     * @param bool $data
     * @return mixed
     * @throws Exception
     */
    public function fetchData($config, $data = false)
    {
        if (is_array($config)) {

            if ($config['type'] === 'lastEmployee') {
                //Last employee
                $sql = 'SELECT em.*
                FROM mc_admin_employee AS em ORDER BY em.id_admin DESC LIMIT 0,1';
                return component_routing_db::layer()->fetch($sql);
            }
        }
    }
    /**
     * @param $config
     * @param bool $data
     * @throws Exception
     */
    public function insert($config,$data = false){
        if (is_array($config)) {
            if ($config['context'] === 'employee') {
                if ($config['type'] === 'newEmployee') {
                    $sql = 'INSERT INTO mc_admin_employee (keyuniqid_admin,title_admin,lastname_admin,firstname_admin,email_admin,active_admin,passwd_admin,last_change_admin)
                			VALUE (:keyuniqid_admin,:title_admin,:lastname_admin,:firstname_admin,:email_admin,:active_admin,:passwd_admin,NOW())';
                    component_routing_db::layer()->insert($sql,
                        array(
                            ':keyuniqid_admin' => $data['keyuniqid_admin'],
                            ':title_admin' => $data['title_admin'],
                            ':lastname_admin' => $data['lastname_admin'],
                            ':firstname_admin' => $data['firstname_admin'],
                            ':email_admin' => $data['email_admin'],
                            ':passwd_admin' => $data['passwd_admin'],
                            ':active_admin' => $data['active_admin']
                        )
                    );
                } else if ($config['type'] === 'employeeRel') {
                    $sql = 'INSERT INTO mc_admin_access_rel (id_admin,id_role)
                			VALUE (:id_admin,:id_role)';
                    component_routing_db::layer()->insert($sql,
                        array(
                            ':id_admin' => $data['id_admin'],
                            ':id_role' => $data['id_role']
                        )
                    );
                }
            }
        }
    }
}
?>