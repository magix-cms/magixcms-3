<?php
class plugins_contact_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config,array $params = []) {
		if ($config['context'] === 'all') {
            	switch ($config['type']) {
					case 'pages':
						$query = 'SELECT h.*,c.*
                    			FROM mc_contact_page AS h
                    			JOIN mc_contact_page_content AS c USING(id_page)
                    			JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    			WHERE h.id_page = :id';
						break;
					case 'contact':
						$query = 'SELECT p.*,c.*,lang.*
								FROM mc_contact AS p
								JOIN mc_contact_content AS c USING(id_contact)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
								WHERE c.id_lang = :default_lang
								GROUP BY p.id_contact';
						break;
					case 'contacts':
						$query = 'SELECT p.id_contact, p.mail_contact
								FROM mc_contact AS p
								JOIN mc_contact_content AS c USING(id_contact)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
								WHERE lang.iso_lang = :lang
								GROUP BY p.id_contact';
						break;
					case 'data':
						$query = 'SELECT p.*,c.*,lang.*
								FROM mc_contact AS p
								JOIN mc_contact_content AS c USING(id_contact)
								JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
								WHERE p.id_contact = :edit';
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
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$query = 'SELECT * FROM mc_contact ORDER BY id_contact DESC LIMIT 0,1';
					break;
				case 'root_page':
					$query = 'SELECT * FROM mc_contact_page ORDER BY id_page DESC LIMIT 0,1';
					break;
				case 'content':
					$query = 'SELECT * FROM `mc_contact_content` WHERE `id_contact` = :id_contact AND `id_lang` = :id_lang';
					break;
				case 'content_page':
					$query = 'SELECT * FROM mc_contact_page_content WHERE id_page = :id AND id_lang = :id_lang';
					break;
				case 'page':
					$query = 'SELECT *
							FROM mc_contact_page as g
							JOIN mc_contact_page_content as gc USING(id_page)
							JOIN mc_lang as l USING(id_lang)
							WHERE iso_lang = :lang
							LIMIT 0,1';
					break;
				case 'config':
					$query = 'SELECT * FROM mc_contact_config ORDER BY id_config DESC LIMIT 0,1';
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
			case 'root_page':
				$query = 'INSERT INTO mc_contact_page(date_register) VALUES (NOW())';
				break;
			case 'contact':
				$query = 'INSERT INTO mc_contact (mail_contact)
						VALUE (:mail_contact)';
				break;
			case 'content':
				$query = 'INSERT INTO `mc_contact_content`(id_contact,id_lang,published_contact) 
						VALUES (:id_contact,:id_lang,:published_contact)';
				break;
			case 'content_page':
				$query = 'INSERT INTO mc_contact_page_content(id_page, id_lang, name_page, content_page, published_page) 
						VALUES (:id, :id_lang, :name_page, :content_page, :published_page)';
				break;
			case 'config':
				$query = 'INSERT INTO `mc_contact_config`(address_enabled,address_required) 
						VALUES (:address_enabled,:address_required)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
    public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'contact':
				$query = 'UPDATE mc_contact 
						SET 
							mail_contact = :mail_contact
						WHERE id_contact = :id_contact';
				break;
			case 'content':
				$query = 'UPDATE mc_contact_content 
						SET 
							published_contact=:published_contact
						WHERE id_contact = :id_contact 
						AND id_lang = :id_lang';
				break;
			case 'content_page':
				$query = 'UPDATE mc_contact_page_content 
						SET 
							name_page = :name_page,
							content_page = :content_page,
							published_page = :published_page
						WHERE id_page = :id 
						AND id_lang = :id_lang';
				break;
			case 'config':
				$query = 'UPDATE mc_contact_config 
						SET 
							address_enabled=:address_enabled,
							address_required=:address_required
						WHERE id_config = :id_config';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
    public function delete(array $config, array $params = []) {
		switch ($config['type']) {
			case 'delMail':
				$query = 'DELETE FROM mc_contact WHERE id_contact IN ('.$params['id'].')';
				$params = array();
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}