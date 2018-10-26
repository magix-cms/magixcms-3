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
						$sql = 'SELECT p.*,c.*,lang.*
								FROM mc_contact AS p
								JOIN mc_contact_content AS c USING(id_contact)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
								WHERE c.id_lang = :default_lang
								GROUP BY p.id_contact';
						break;
					case 'contacts':
						$sql = 'SELECT p.id_contact, p.mail_contact
								FROM mc_contact AS p
								JOIN mc_contact_content AS c USING(id_contact)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
								WHERE lang.iso_lang = :lang
								GROUP BY p.id_contact';
						break;
					case 'data':
						$sql = 'SELECT p.*,c.*,lang.*
								FROM mc_contact AS p
								JOIN mc_contact_content AS c USING(id_contact)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
								WHERE p.id_contact = :edit';
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
	 * @throws Exception
	 */
    public function insert($config, $params = array())
    {
        if (is_array($config)) {
            $sql = '';

			switch ($config['type']) {
				case 'contact':
					$sql = 'INSERT INTO mc_contact (mail_contact)
                			VALUE (:mail_contact)';
					break;
				case 'content':
					$sql = 'INSERT INTO `mc_contact_content`(id_contact,id_lang,published_contact) 
				  			VALUES (:id_contact,:id_lang,:published_contact)';
					break;
				case 'config':
					$sql = 'INSERT INTO `mc_contact_config`(address_enabled,address_required) 
				  			VALUES (:address_enabled,:address_required)';
					break;
			}

            if($sql !== '') component_routing_db::layer()->insert($sql,$params);
        }
    }

	/**
	 * @param $config
	 * @param array $data
	 * @throws Exception
	 */
    public function update($config, $params = array())
    {
        if (is_array($config)) {
            $sql = '';

			switch ($config['type']) {
				case 'contact':
					$sql = 'UPDATE mc_contact 
							SET 
								mail_contact = :mail_contact
							WHERE id_contact = :id_contact';
					break;
				case 'content':
					$sql = 'UPDATE mc_contact_content 
							SET 
								published_contact=:published_contact
							WHERE id_contact = :id_contact 
							AND id_lang = :id_lang';
					break;
				case 'config':
					$sql = 'UPDATE mc_contact_config 
							SET 
								address_enabled=:address_enabled,
								address_required=:address_required
							WHERE id_config = :id_config';
					break;
			}

            if($sql !== '') component_routing_db::layer()->update($sql,$params);
        }
    }

	/**
	 * @param $config
	 * @param array $params
	 * @throws Exception
	 */
    public function delete($config, $params = array())
    {
        if (is_array($config)) {
			$sql = '';

			switch ($config['type']) {
				case 'delMail':
					$sql = 'DELETE FROM mc_contact WHERE id_contact IN ('.$params['id'].')';
					$params = array();
					break;
			}

			if ($sql !== '') component_routing_db::layer()->delete($sql,$params);
        }
    }
}