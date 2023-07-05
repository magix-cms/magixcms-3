<?php
class backend_db_snippet {
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
                case 'pages':
                    $cond = '';
                    $limit = '';
                    if($config['offset']) {
                        $limit = ' LIMIT 0, '.$config['offset'];
                        if(isset($config['page']) && $config['page'] > 1) {
                            $limit = ' LIMIT '.(($config['page'] - 1) * $config['offset']).', '.$config['offset'];
                        }
                    }

                    $query = "SELECT 
								st.*
							FROM mc_snippet AS st
							ORDER BY st.id_snippet DESC".$limit;
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
                case 'page':
                    $query = 'SELECT 
								st.*
							FROM mc_snippet AS st
							WHERE st.id_snippet = :id';
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
            case 'page':
                $query = "INSERT INTO `mc_snippet`(title_sp, description_sp, content_sp, date_register) 
                        VALUE (:title_sp, :description_sp, :content_sp, NOW())";
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
            case 'page':
                $query = 'UPDATE mc_snippet 
							SET 
								title_sp=:title_sp, 
							    description_sp=:description_sp, 
							    content_sp=:content_sp

							WHERE id_snippet = :id_snippet';
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
            case 'delPages':
                $query = 'DELETE FROM mc_snippet 
						WHERE id_snippet IN ('.$params['id'].')';
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
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
    }
}