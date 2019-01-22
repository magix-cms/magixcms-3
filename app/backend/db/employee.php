<?php
class backend_db_employee
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed
     * @throws Exception
     */
    public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'access':
					$sql = 'SELECT access.* ,module.*
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
					$sql = "SELECT em.id_admin,em.title_admin,em.firstname_admin,em.lastname_admin,em.email_admin,pr.role_name,em.active_admin
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS acrel ON( em.id_admin = acrel.id_admin )
							JOIN mc_admin_role_user AS pr ON( acrel.id_role = pr.id_role )
							$cond ORDER BY em.id_admin DESC";
					break;
				case 'jobs':
					$sql = 'SELECT jobs.* FROM mc_admin_jobs AS jobs';
					break;
				case 'LastJobs':
					$sql = 'SELECT jobs.* FROM mc_admin_jobs AS jobs ORDER BY jobs.id_job DESC LIMIT 0,1';
					break;
				case 'roles':
					$sql = 'SELECT * FROM mc_admin_role_user';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'mail':
					$sql = 'SELECT em.email_admin, em.passwd_admin 
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS rel ON ( em.id_admin = rel.id_admin )
							WHERE em.email_admin = :email_admin
							AND em.active_admin = 1 AND rel.id_admin = em.id_admin';
					break;
				case 'auth':
					$sql = 'SELECT em.* 
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS rel ON ( em.id_admin = rel.id_admin )
							WHERE em.email_admin = :email_admin AND em.passwd_admin = :passwd_admin
							AND em.active_admin = 1 AND rel.id_admin = em.id_admin';
					break;
				case 'session':
					$sql = 'SELECT em.*, pr.role_name, pr.id_role
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS rel ON ( em.id_admin = rel.id_admin )
							JOIN mc_admin_role_user AS pr ON ( rel.id_role = pr.id_role )
							WHERE em.keyuniqid_admin = :keyuniqid_admin';
					break;
				case 'sid':
					$sql = 'SELECT id_admin_session,id_admin FROM mc_admin_session WHERE id_admin_session = :id_admin_session';
					$params = array('id_admin_session' => session_id());
					break;
				case 'currentAccess':
					$sql = 'SELECT * FROM mc_admin_access as acc
							JOIN mc_module as mods ON(acc.id_module = mods.id_module)
							WHERE id_role = :id_role AND class_name = :class_name';
					break;
				case 'role':
					$sql = 'SELECT * FROM mc_admin_role_user';
					break;
				case 'currentRole':
					$sql = 'SELECT role.* FROM mc_admin_role_user AS role
							JOIN mc_admin_access_rel AS rel_access ON(role.id_role = rel_access.id_role)
							WHERE rel_access.id_admin = :id_admin';
					break;
				case 'key':
					$sql = 'SELECT keyuniqid_admin FROM mc_admin_employee WHERE email_admin = :email_forgot';
					break;
				case 'by_key':
					$sql = 'SELECT * FROM mc_admin_employee
							WHERE change_passwd = :ticket
							AND keyuniqid_admin = :keyuniqid_admin';
					break;
				case 'employee':
					$sql = 'SELECT em.*,pr.*
							FROM mc_admin_employee AS em
							JOIN mc_admin_access_rel AS acrel ON( em.id_admin = acrel.id_admin )
							JOIN mc_admin_role_user AS pr ON( acrel.id_role = pr.id_role )
							WHERE em.id_admin = :id';
					break;
				case 'lastEmployee':
					$sql = 'SELECT em.* FROM mc_admin_employee AS em ORDER BY em.id_admin DESC LIMIT 0,1';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
    }

    /**
     * @param $config
     * @param array $params
	 * @return bool|string
     */
	public function insert($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'newSession':
				$sql = 'INSERT INTO mc_admin_session (id_admin_session,id_admin,ip_session,browser_admin,keyuniqid_admin,expires)
                    	VALUE (:id_admin_session,:id_admin,:ip_session,:browser_admin,:keyuniqid_admin,:expires)';
				break;
			case 'newEmployee':
				$sql = 'INSERT INTO mc_admin_employee (keyuniqid_admin,title_admin,lastname_admin,firstname_admin,email_admin,phone_admin,address_admin,postcode_admin,city_admin,country_admin,active_admin,passwd_admin,last_change_admin)
						VALUE (:keyuniqid_admin,:title_admin,:lastname_admin,:firstname_admin,:email_admin,:phone_admin,:address_admin,:postcode_admin,:city_admin,:country_admin,:active_admin,:passwd_admin,NOW())';
				break;
			case 'employeeRel':
				$sql = 'INSERT INTO mc_admin_access_rel (id_admin,id_role)
						VALUE (:id_admin,:id_role)';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
	public function update($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'newSession':
				$sql = 'INSERT INTO mc_admin_session (id_admin_session,id_admin,ip_session,browser_admin,keyuniqid_admin)
                    	VALUE (:id_admin_session,:id_admin,:ip_session,:browser_admin,:keyuniqid_admin)';
				break;
			case 'employee':
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
				break;
			case 'role':
				$sql = 'UPDATE mc_admin_access_rel SET
							id_role = :id_role
 						  	WHERE id_admin = :id_admin';
				break;
			case 'employeePwd':
				$sql = 'UPDATE mc_admin_employee SET passwd_admin = :passwd_admin WHERE id_admin = :id_admin';
				break;
			case 'employeeActive':
				$sql = 'UPDATE mc_admin_employee SET active_admin = :active_admin WHERE id_admin IN ('.$params['id_admin'].')';
				break;
			case 'askPassword':
				$sql = 'UPDATE mc_admin_employee SET change_passwd = :change_passwd WHERE email_admin = :email_admin';
				break;
			case 'newPassword':
				$sql = 'UPDATE mc_admin_employee SET passwd_admin = :passwd_admin, change_passwd = NULL WHERE email_admin = :email_admin';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';
		$sql = '';

		switch ($config['type']) {
			case 'lastSession':
				$sql = 'DELETE FROM mc_admin_session
                		WHERE TO_DAYS(DATE_FORMAT(NOW(), "%Y%m%d")) - TO_DAYS(DATE_FORMAT(last_modified_session, "%Y%m%d")) > :limit AND id_admin = :id_admin';
				break;
			case 'currentSession':
				$sql = 'DELETE FROM mc_admin_session WHERE id_admin = :id_admin';
				break;
			case 'sidSession':
				$sql = 'DELETE FROM mc_admin_session WHERE id_admin_session = :id_admin_session';
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
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}