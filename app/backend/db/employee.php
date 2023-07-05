<?php
class backend_db_employee {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

    /**
     * @param array $config
     * @param array $params
     * @return array|bool
     */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'access':
					$query = 'SELECT access.* ,module.*
							FROM mc_admin_access AS access
							JOIN mc_module as module ON(access.id_module = module.id_module)
							WHERE access.id_role = :id_role';
					break;
				case 'employees':
					$cond = '';
					if(isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
						$nbc = 0;
						$params = array();
						foreach ($config['search'] as $key => $q) {
							if($q !== '') {
								if($nbc > 0) {
									$cond .= 'AND ';
								} else {
									$cond = 'WHERE ';
								}
								$params[$key] = $q;
								switch ($key) {
									case 'id_admin':
									case 'active_admin':
										$cond .= 'em.'.$key.' = :'.$key.' ';
										break;
									case 'title_admin':
										$cond .= "em.".$key." = :'".$key."' ";
										break;
									case 'firstname_admin':
									case 'lastname_admin':
									case 'email_admin':
										$cond .= "em.".$key." LIKE '%:".$key."%' ";
										break;
									case 'role':
										$cond .= "pr.role_name LIKE '%:".$key."%' ";
										break;
								}
								$nbc++;
							}
						}
					}
					$query = "SELECT em.id_admin,em.title_admin,em.firstname_admin,em.lastname_admin,em.email_admin,pr.role_name,em.active_admin
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS acrel ON( em.id_admin = acrel.id_admin )
							JOIN mc_admin_role_user AS pr ON( acrel.id_role = pr.id_role )
							$cond ORDER BY em.id_admin DESC";
					break;
				case 'roles':
					$query = 'SELECT * FROM mc_admin_role_user';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'mail':
					$query = 'SELECT em.email_admin, em.passwd_admin 
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS rel ON ( em.id_admin = rel.id_admin )
							WHERE em.email_admin = :email_admin
							AND em.active_admin = 1 AND rel.id_admin = em.id_admin';
					break;
				case 'auth':
					$query = 'SELECT em.* 
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS rel ON ( em.id_admin = rel.id_admin )
							WHERE em.email_admin = :email_admin AND em.passwd_admin = :passwd_admin
							AND em.active_admin = 1 AND rel.id_admin = em.id_admin';
					break;
				case 'session':
					$query = 'SELECT em.*, pr.role_name, pr.id_role
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS rel ON ( em.id_admin = rel.id_admin )
							JOIN mc_admin_role_user AS pr ON ( rel.id_role = pr.id_role )
							WHERE em.keyuniqid_admin = :keyuniqid_admin';
					break;
				case 'sid':
					$query = 'SELECT id_admin_session,id_admin FROM mc_admin_session WHERE id_admin_session = :id_admin_session';
					$params = array('id_admin_session' => session_id());
					break;
				case 'currentAccess':
					$query = 'SELECT * FROM mc_admin_access as acc
							JOIN mc_module as mods ON(acc.id_module = mods.id_module)
							WHERE id_role = :id_role AND class_name = :class_name';
					break;
				case 'role':
					$query = 'SELECT * FROM mc_admin_role_user';
					break;
				case 'currentRole':
					$query = 'SELECT role.* FROM mc_admin_role_user AS role
							JOIN mc_admin_access_rel AS rel_access ON(role.id_role = rel_access.id_role)
							WHERE rel_access.id_admin = :id_admin';
					break;
				case 'key':
					$query = 'SELECT keyuniqid_admin FROM mc_admin_employee WHERE email_admin = :email_forgot';
					break;
				case 'by_key':
					$query = 'SELECT * FROM mc_admin_employee
							WHERE change_passwd = :ticket
							AND keyuniqid_admin = :keyuniqid_admin';
					break;
				case 'employee':
					$query = 'SELECT em.*,pr.*
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS acrel ON( em.id_admin = acrel.id_admin )
							JOIN mc_admin_role_user AS pr ON( acrel.id_role = pr.id_role )
							WHERE em.id_admin = :id';
					break;
				case 'lastEmployee':
					$query = 'SELECT em.* FROM mc_admin_employee AS em ORDER BY em.id_admin DESC LIMIT 0,1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool|string
     */
	public function insert(array $config, array $params = []) {
		switch ($config['type']) {
			case 'newSession':
				$query = 'INSERT INTO mc_admin_session (id_admin_session,id_admin,ip_session,browser_admin,keyuniqid_admin,expires)
                    	VALUE (:id_admin_session,:id_admin,:ip_session,:browser_admin,:keyuniqid_admin,:expires)';
				break;
			case 'newEmployee':
				$query = 'INSERT INTO mc_admin_employee (keyuniqid_admin,title_admin,lastname_admin,firstname_admin,email_admin,phone_admin,address_admin,postcode_admin,city_admin,country_admin,active_admin,passwd_admin,last_change_admin)
						VALUE (:keyuniqid_admin,:title_admin,:lastname_admin,:firstname_admin,:email_admin,:phone_admin,:address_admin,:postcode_admin,:city_admin,:country_admin,:active_admin,:passwd_admin,NOW())';
				break;
			case 'employeeRel':
				$query = 'INSERT INTO mc_admin_access_rel (id_admin,id_role)
						VALUE (:id_admin,:id_role)';
				break;
			default:
				return false;
		}
		
		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
	}

    /**
     * @param array $config
     * @param array $params
     * @return bool|string
     */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'newSession':
				$query = 'INSERT INTO mc_admin_session (id_admin_session,id_admin,ip_session,browser_admin,keyuniqid_admin)
                    	VALUE (:id_admin_session,:id_admin,:ip_session,:browser_admin,:keyuniqid_admin)';
				break;
			case 'employee':
				$query = 'UPDATE mc_admin_employee SET
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
				break;
			case 'role':
				$query = 'UPDATE mc_admin_access_rel SET
							id_role = :id_role
 						  	WHERE id_admin = :id_admin';
				break;
			case 'employeePwd':
				$query = 'UPDATE mc_admin_employee SET passwd_admin = :passwd_admin WHERE id_admin = :id_admin';
				break;
			case 'employeeActive':
				$query = 'UPDATE mc_admin_employee SET active_admin = :active_admin WHERE id_admin IN ('.$params['id_admin'].')';
				break;
			case 'askPassword':
				$query = 'UPDATE mc_admin_employee SET change_passwd = :change_passwd WHERE email_admin = :email_admin';
				break;
			case 'newPassword':
				$query = 'UPDATE mc_admin_employee SET passwd_admin = :passwd_admin, change_passwd = NULL WHERE email_admin = :email_admin';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
		switch ($config['type']) {
			case 'lastSession':
				$query = "DELETE FROM mc_admin_session
                		WHERE TO_DAYS(DATE_FORMAT(NOW(), '%Y%m%d')) - TO_DAYS(DATE_FORMAT(last_modified_session, '%Y%m%d')) > :limit AND id_admin = :id_admin";
				break;
			case 'currentSession':
				$query = 'DELETE FROM mc_admin_session WHERE id_admin = :id_admin';
				break;
			case 'sidSession':
				$query = 'DELETE FROM mc_admin_session WHERE id_admin_session = :id_admin_session';
				break;
			case 'delEmployees':
				$queries = array(
					array('request'=>'DELETE emp.*, acr.* FROM mc_admin_employee AS emp LEFT JOIN mc_admin_access_rel AS acr ON emp.id_admin = acr.id_admin WHERE emp.id_admin IN('.$params['id'].')','params'=>array()),
					array('request'=>'DELETE FROM mc_admin_session WHERE id_admin IN('.$params['id'].')','params'=>array()),
				);

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
	}
}