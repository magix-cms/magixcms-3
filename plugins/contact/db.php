<?php
class plugins_contact_db
{
	/**
	 * @param $config
	 * @$params bool $data
	 * @return mixed|null
	 * @throws Exception
	 */
    public function fetchData($config, $params = false)
    {
        $sql = '';

        if (is_array($config)) {
            if ($config['context'] === 'all') {
            	switch ($config['type']) {
					case 'contact':
						$sql = 'SELECT 
									c.id_contact,
									c.email_contact,
									cc.active_contact
								FROM mc_contact AS c
								JOIN mc_contact_content AS cc USING(id_contact)
								JOIN mc_lang AS lang ON(cc.id_lang = lang.id_lang)
								WHERE cc.id_lang = :default_lang
								ORDER BY c.id_contact';

						if(isset($config['search'])) {
							$cond = '';
							$config['search'] = array_filter($config['search']);
							if(is_array($config['search']) && !empty($config['search'])) {
								$nbc = 0;
								foreach ($config['search'] as $key => $q) {
									if($q != '') {
										$cond .= 'AND ';
										switch ($key) {
											case 'id_contact':
												$cond .= 'c.'.$key.' = '.$q.' ';
												break;
											case 'email_contact':
												$cond .= "c.".$key." LIKE '%".$q."%' ";
												break;
											case 'active_contact':
												$cond .= 'cc.'.$key.' = '.$q.' ';
												break;
										}
										$nbc++;
									}
								}

								$sql = "SELECT 
											c.id_contact,
											c.email_contact,
											cc.active_contact
										FROM mc_contact AS c
										JOIN mc_contact_content AS cc USING(id_contact)
										JOIN mc_lang AS lang ON(cc.id_lang = lang.id_lang)
										WHERE cc.id_lang = :default_lang
										$cond
										ORDER BY c.id_contact";
							}
						}
						break;
					case 'contacts':
						$sql = 'SELECT 
									c.id_contact, 
									c.email_contact
								FROM mc_contact AS c
								JOIN mc_contact_content AS cc USING(id_contact)
								JOIN mc_lang AS lang ON(cc.id_lang = lang.id_lang)
								WHERE lang.iso_lang = :lang
								AND cc.active_contact = 1
								ORDER BY c.id_contact';
						break;
					case 'data':
						$sql = 'SELECT 
									c.id_contact,
									c.email_contact,
									cc.id_lang,
									cc.active_contact
								FROM mc_contact AS c
								JOIN mc_contact_content AS cc USING(id_contact)
								JOIN mc_lang AS lang ON(cc.id_lang = lang.id_lang)
								WHERE c.id_contact = :id';
						break;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
				switch ($config['type']) {
					case 'root':
						$sql = 'SELECT * FROM mc_contact ORDER BY id_contact DESC LIMIT 0,1';
						break;
					case 'content':
						$sql = 'SELECT * FROM `mc_contact_content` WHERE `id_contact` = :id_contact AND `id_lang` = :id_lang';
						break;
					case 'config':
						$sql = 'SELECT * FROM mc_contact_config ORDER BY id_config DESC LIMIT 0,1';
						break;
				}

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
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
			case 'contact':
				$sql = 'INSERT INTO mc_contact (email_contact)
						VALUE (:email_contact)';
				break;
			case 'content':
				$sql = 'INSERT INTO `mc_contact_content`(id_contact,id_lang,active_contact) 
						VALUES (:id_contact,:id_lang,:active_contact)';
				break;
			case 'config':
				$sql = 'INSERT INTO `mc_contact_config`(address_enabled,address_required) 
						VALUES (:address_enabled,:address_required)';
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
			case 'contact':
				$sql = 'UPDATE mc_contact 
						SET 
							email_contact = :email_contact
						WHERE id_contact = :id_contact';
				break;
			case 'content':
				$sql = 'UPDATE mc_contact_content 
						SET 
							active_contact = :active_contact
						WHERE id_contact = :id_contact 
						AND id_lang = :id_lang';
				break;
			case 'config':
				$sql = 'UPDATE mc_contact_config 
						SET 
							address_enabled = :address_enabled,
							address_required = :address_required
						WHERE id_config = :id_config';
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
			case 'contact':
				$sql = 'DELETE FROM mc_contact WHERE id_contact IN ('.$params['id'].')';
				$params = array();
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