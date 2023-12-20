<?php
class backend_db_logo {
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
		if ($config['context'] === 'all') {
			switch ($config['type']) {
                case 'page':
                    $query = 'SELECT p.*,c.*,lang.*
							FROM mc_logo AS p
							JOIN mc_logo_content AS c USING(id_logo)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_logo = :edit';
                    break;
                case 'placeholder':
                    $query = "SELECT * FROM mc_setting WHERE name IN ('holder_bg_color','logo_percent')";
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
                    $query = 'SELECT * FROM mc_logo ORDER BY id_logo DESC LIMIT 0,1';
                    break;
                case 'content':
                    $query = 'SELECT * FROM `mc_logo_content` WHERE `id_logo` = :id_logo AND `id_lang` = :id_lang';
                    break;
				case 'page':
					$query = 'SELECT p.img_logo,p.active_logo,c.alt_logo,c.title_logo,lang.iso_lang,lang.id_lang
							FROM mc_logo AS p
							JOIN mc_logo_content AS c USING(id_logo)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE lang.iso_lang = :iso LIMIT 0,1';
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
            case 'logo':
                $query = "INSERT INTO `mc_logo`(img_logo,active_logo,date_register) 
						VALUES (:img_logo,:active_logo,NOW())";
                break;
            case 'imgContent':
                $query = 'INSERT INTO `mc_logo_content`(id_logo,id_lang,alt_logo,title_logo,last_update) 
				  		VALUES (:id_logo,:id_lang,:alt_logo,:title_logo,NOW())';
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
            case 'logo':
                $query = 'UPDATE mc_logo
						SET img_logo = :img_logo,
						    active_logo = :active_logo
                		WHERE id_logo = :id_logo';
                break;
            case 'active':
                $query = 'UPDATE mc_logo
						SET active_logo = :active_logo
                		WHERE id_logo = :id_logo';
                break;
            case 'imgContent':
                $query = 'UPDATE mc_logo_content 
						SET 
							alt_logo = :alt_logo,
							title_logo = :title_logo
                		WHERE id_logo = :id_logo 
                		AND id_lang = :id_lang';
                break;
            case 'placeholder':
                $query = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'holder_bg_color' THEN :holder_bg_color
							WHEN 'logo_percent' THEN :logo_percent
						END
						WHERE `name` IN ('holder_bg_color','logo_percent')";
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
}