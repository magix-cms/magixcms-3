<?php
class backend_db_employee
{
    /**
     * @param $config
     * @param bool $data
     * @return mixed
     * @throws Exception
     */
    public function fetchData($config,$data = false){
        if(is_array($config)) {
            if ($config['type'] === 'mail') {
                //Return Auth status
                $query='SELECT em.email_admin,em.passwd_admin from mc_admin_employee AS em
                    JOIN mc_admin_access_rel AS acrel ON ( em.id_admin = acrel.id_admin )
                    WHERE em.email_admin = :email_admin
                    AND em.active_admin = 1 AND acrel.id_admin=em.id_admin';
                return component_routing_db::layer()->fetch($query,array(
                        ':email_admin'  => $data['email_admin']
                    )
                );
            } elseif ($config['type'] === 'auth') {
                //Return Auth status
                $query='SELECT em.* from mc_admin_employee AS em
                JOIN mc_admin_access_rel AS acrel ON ( em.id_admin = acrel.id_admin )
                WHERE em.email_admin = :email_admin AND em.passwd_admin = :passwd_admin
                AND em.active_admin = 1 AND acrel.id_admin=em.id_admin';
                return component_routing_db::layer()->fetch($query,array(
                        ':email_admin'  => $data['email_admin'],
                        ':passwd_admin' => $data['passwd_admin']
                    )
                );
            } elseif ($config['type'] === 'session') {
                // return session data
                $sql='SELECT em.*, pr.role_name, pr.id_role
                FROM mc_admin_employee AS em
                JOIN mc_admin_access_rel AS acrel ON ( em.id_admin = acrel.id_admin )
                JOIN mc_admin_role_user AS pr ON ( acrel.id_role = pr.id_role )
                WHERE em.keyuniqid_admin = :keyuniqid_admin';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':keyuniqid_admin'=> $data['keyuniqid_admin']
                    )
                );
            } elseif($config['type'] === 'sid') {
                //return session id for compare
                $sql = 'SELECT id_admin_session,id_admin
		        FROM mc_admin_session WHERE id_admin_session = :id_admin_session';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':id_admin_session'=>session_id()
                    )
                );
            } elseif($config['type'] === 'uniq_session') {
                //return session id for compare
                $sql = 'SELECT id_admin_session,email_admin,sess.keyuniqid_admin
		        FROM mc_admin_session as sess
		        LEFT JOIN mc_admin_employee as emp
		        USING(id_admin)
		        WHERE email_admin = :email_admin
		        AND sess.keyuniqid_admin = :keyuniqid_admin
		        AND id_admin_session = :id_admin_session LIMIT 0,1';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':email_admin' => $data['m'],
                        ':keyuniqid_admin' => $data['k'],
                        ':id_admin_session' => $data['t']
                    )
                );
            } elseif($config['type'] === 'currentAccess') {
                // return current access (permission)
                $sql='SELECT * FROM mc_admin_access
                JOIN mc_module as module ON(access.id_module = module.id_module)
                WHERE id_role = :id_role AND class_name = :class_name';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':id_role'      => $data['id_role'],
                        ':class_name'   => $data['class_name']
                    )
                );
            } elseif($config['type'] === 'access') {
                // return listing access
                $sql='SELECT access.* ,module.*
                FROM mc_admin_access AS access
                JOIN mc_module as module ON(access.id_module = module.id_module)
                WHERE access.id_role = :id_role';
                return component_routing_db::layer()->fetchAll($sql,
                    array(
                        ':id_role'=> $data['id_role']
                    )
                );
            } elseif($config['type'] === 'role') {
                //Return role list
                $sql='SELECT * FROM mc_admin_role_user';
                return component_routing_db::layer()->fetchAll($sql);
                
            } elseif($config['type'] === 'currentRole') {
                $sql='SELECT role.* FROM mc_admin_role_user AS role
                JOIN mc_admin_access_rel AS rel_access ON(role.id_role = rel_access.id_role)
                WHERE rel_access.id_admin = :id_admin';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':id_admin'=> $data['id_admin']
                    )
                );
            } elseif($config['type'] === 'key') {
                $sql='SELECT keyuniqid_admin FROM mc_admin_employee
                WHERE email_admin = :email_forgot';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':email_forgot'=> $data
                    )
                );
            } elseif($config['type'] === 'by_key') {
                $sql='SELECT email_admin FROM mc_admin_employee
                WHERE change_passwd = :ticket
                AND keyuniqid_admin = :keyuniqid_admin';
                return component_routing_db::layer()->fetch($sql,
                    array(
                        ':ticket'=> $data['ticket'],
                        ':keyuniqid_admin'=> $data['key'],
                    )
                );
            } elseif($config['type'] === 'employees') {
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
								case 'id_admin':
								case 'active_admin':
									$cond .= 'em.'.$key.' = '.$q.' ';
									break;
								case 'title_admin':
									$cond .= "em.".$key." = '".$q."' ";
									break;
								case 'firstname_admin':
								case 'lastname_admin':
								case 'email_admin':
									$cond .= "em.".$key." LIKE '%".$q."%' ";
									break;
								case 'role':
									$cond .= "pr.role_name LIKE '%".$q."%' ";
									break;
							}
							$nbc++;
						}
					}
				}
                $sql="SELECT em.id_admin,em.title_admin,em.firstname_admin,em.lastname_admin,em.email_admin,pr.role_name,em.active_admin
                FROM mc_admin_employee AS em
                JOIN mc_admin_access_rel AS acrel ON( em.id_admin = acrel.id_admin )
                JOIN mc_admin_role_user AS pr ON( acrel.id_role = pr.id_role )
                $cond ORDER BY em.id_admin DESC";
                return component_routing_db::layer()->fetchAll($sql);
            } elseif($config['type'] === 'employee') {
				$sql="SELECT em.*,pr.*
                FROM mc_admin_employee AS em
                JOIN mc_admin_access_rel AS acrel ON( em.id_admin = acrel.id_admin )
                JOIN mc_admin_role_user AS pr ON( acrel.id_role = pr.id_role )
                WHERE em.id_admin = :id";
                return component_routing_db::layer()->fetch($sql,$data);
            } elseif($config['type'] === 'lastEmployee') {
                //Last employee
                $sql = 'SELECT em.*
                FROM mc_admin_employee AS em ORDER BY em.id_admin DESC LIMIT 0,1';
                return component_routing_db::layer()->fetch($sql);
            } elseif($config['type'] === 'jobs') {
                //List job
                $sql = 'SELECT jobs.* 
                FROM mc_admin_jobs AS jobs';
                return component_routing_db::layer()->fetchAll($sql);
            } elseif($config['type'] === 'LastJobs') {
                //List job
                $sql = 'SELECT jobs.* 
                FROM mc_admin_jobs AS jobs ORDER BY jobs.id_job DESC LIMIT 0,1';
                return component_routing_db::layer()->fetchAll($sql);
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     * @throws Exception
     */
    public function delete($config,$data = false)
    {
        if (is_array($config)) {
        	if($config['context'] === 'session') {
				if ($config['type'] === 'lastSession') {
					$sql = 'DELETE FROM mc_admin_session
                WHERE TO_DAYS(DATE_FORMAT(NOW(), "%Y%m%d")) - TO_DAYS(DATE_FORMAT(last_modified_session, "%Y%m%d")) > :limit AND id_admin = :id_admin';
					component_routing_db::layer()->delete($sql,
						array(
							':limit'    => $data['limit'],
							':id_admin' => $data['id_admin']
						)
					);
				}elseif($config['type'] === 'currentSession'){
					$sql = 'DELETE FROM mc_admin_session
                WHERE id_admin = :id_admin';
					component_routing_db::layer()->delete($sql,
						array(
							':id_admin' => $data['id_admin']
						));
				}elseif($config['type'] === 'sidSession'){
					$sql = 'DELETE FROM mc_admin_session
                WHERE id_admin_session = :id_admin_session';
					component_routing_db::layer()->delete($sql,
						array(
							':id_admin_session' => $data['id_admin_session']
						));
				}
			}elseif($config['context'] === 'employee'){
				if($config['type'] === 'delEmployees') {
                    $queries = array(
                        array('request'=>'DELETE emp.*, acr.* FROM mc_admin_employee AS emp LEFT JOIN mc_admin_access_rel AS acr ON emp.id_admin = acr.id_admin WHERE emp.id_admin IN('.$data['id'].')','params'=>array()),
                        array('request'=>'DELETE FROM mc_admin_session WHERE id_admin IN('.$data['id'].')','params'=>array()),
                    );
					component_routing_db::layer()->transaction($queries);
				}
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
			if ($config['context'] === 'session') {
				if ($config['type'] === 'newSession') {
					$sql = 'INSERT INTO mc_admin_session (id_admin_session,id_admin,ip_session,browser_admin,keyuniqid_admin)
                    VALUE (:id_admin_session,:id_admin,:ip_session,:browser_admin,:keyuniqid_admin)';
					component_routing_db::layer()->insert($sql,
						array(
							':id_admin_session' => $data['id_admin_session'],
							':id_admin'         => $data['id_admin'],
							':ip_session'       => $data['ip_session'],
							':browser_admin'    => $data['browser_admin'],
							':keyuniqid_admin'  => $data['keyuniqid_admin']
						));
				}
			}elseif ($config['context'] === 'employee') {
				if ($config['type'] === 'newEmployee') {
					$sql = 'INSERT INTO mc_admin_employee (keyuniqid_admin,title_admin,lastname_admin,firstname_admin,email_admin,phone_admin,address_admin,postcode_admin,city_admin,country_admin,active_admin,passwd_admin,last_change_admin)
                			VALUE (:keyuniqid_admin,:title_admin,:lastname_admin,:firstname_admin,:email_admin,:phone_admin,:address_admin,:postcode_admin,:city_admin,:country_admin,:active_admin,:passwd_admin,NOW())';
					component_routing_db::layer()->insert($sql,
						array(
							':keyuniqid_admin' => $data['keyuniqid_admin'],
							':title_admin' => $data['title_admin'],
							':lastname_admin' => $data['lastname_admin'],
							':firstname_admin' => $data['firstname_admin'],
							':email_admin' => $data['email_admin'],
							':phone_admin'   => $data['phone_admin'],
							':address_admin' => $data['address_admin'],
							':postcode_admin'=> $data['postcode_admin'],
							':city_admin'    => $data['city_admin'],
							':country_admin' => $data['country_admin'],
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

    /**
     * @param $config
     * @param bool $data
     * @throws Exception
     */
	public function update($config,$data = false){
		if (is_array($config)) {
			if ($config['context'] === 'session') {
				if ($config['type'] === 'newSession') {
					$sql = 'INSERT INTO mc_admin_session (id_admin_session,id_admin,ip_session,browser_admin,keyuniqid_admin)
                    VALUE (:id_admin_session,:id_admin,:ip_session,:browser_admin,:keyuniqid_admin)';
					component_routing_db::layer()->update($sql,
						array(
							':id_admin_session' => $data['id_admin_session'],
							':id_admin'         => $data['id_admin'],
							':ip_session'       => $data['ip_session'],
							':browser_admin'    => $data['browser_admin'],
							':keyuniqid_admin'  => $data['keyuniqid_admin']
						));
				}
			}elseif ($config['context'] === 'employee') {
				if ($config['type'] === 'employee') {
					$sql = 'UPDATE mc_admin_employee SET
								title_admin = :title_admin,
								lastname_admin = :lastname_admin,
								firstname_admin = :firstname_admin,
								email_admin = :email_admin,
								phone_admin = :phone_admin,
								address_admin = :address_admin,
								postcode_admin = :postcode_admin,
								city_admin = :city_admin,
								country_admin = :country_admin,
								active_admin = :active_admin,
								last_change_admin = NOW()
 						  	WHERE id_admin = :id_admin';
					component_routing_db::layer()->update($sql,
						array(
							':title_admin' => $data['title_admin'],
							':lastname_admin' => $data['lastname_admin'],
							':firstname_admin' => $data['firstname_admin'],
							':email_admin' => $data['email_admin'],
							':phone_admin'   => $data['phone_admin'],
							':address_admin' => $data['address_admin'],
							':postcode_admin'=> $data['postcode_admin'],
							':city_admin'    => $data['city_admin'],
							':country_admin' => $data['country_admin'],
							':active_admin' => $data['active_admin'],
							':id_admin' => $data['id_admin']
						)
					);
				} elseif ($config['type'] === 'employeePwd') {
					$sql = 'UPDATE mc_admin_employee SET passwd_admin = :passwd_admin WHERE id_admin = :id_admin';
					component_routing_db::layer()->update($sql,
						array(
							':passwd_admin' => $data['passwd_admin'],
							':id_admin' => $data['id_admin']
						)
					);
				} elseif ($config['type'] === 'employeeActive') {
					$sql = 'UPDATE mc_admin_employee SET active_admin = :active_admin WHERE id_admin IN ('.$data['id_admin'].')';
					component_routing_db::layer()->update($sql,
						array(
							':active_admin' => $data['active_admin']
						)
					);
				} elseif ($config['type'] === 'askPassword') {
					$sql = 'UPDATE mc_admin_employee SET change_passwd = :change_passwd WHERE email_admin = :email_admin';
					component_routing_db::layer()->update($sql,
						array(
							':change_passwd' => $data['token'],
							':email_admin' => $data['email_admin']
						)
					);
				} elseif ($config['type'] === 'newPassword') {
					$sql = 'UPDATE mc_admin_employee SET passwd_admin = :passwd_admin, change_passwd = NULL WHERE email_admin = :email_admin';
					component_routing_db::layer()->update($sql,
						array(
							':passwd_admin' => $data['newPassword'],
							':email_admin' => $data['email_admin']
						)
					);
				}
			}
		}
	}
}
?>