<?php
class backend_db_revisions {
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
        $dateFormat = new component_format_date();

        if ($config['context'] === 'all') {
            switch ($config['type']) {
                case 'historyList':
                    $query = "SELECT id, date_register 
                        FROM mc_revisions_editor 
                        WHERE item_type = :item_type 
                          AND item_id = :item_id 
                          AND id_lang = :id_lang 
                          AND editor_id = :field 
                        ORDER BY date_register DESC 
                        LIMIT 10";
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
                case 'revisionContent':
                    $query = 'SELECT content 
                        FROM mc_revisions_editor 
                        WHERE id = :id 
                        LIMIT 1';
                    break;
                case 'lastRevision':
                    $query = 'SELECT content 
                        FROM mc_revisions_editor 
                        WHERE item_type = :item_type 
                          AND item_id = :item_id 
                          AND id_lang = :id_lang 
                          AND editor_id = :field 
                        ORDER BY date_register DESC 
                        LIMIT 1';
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
    public function insert(string $type, array $params = []): bool {
        switch ($type) {
            case 'addRevision':
                $query = 'INSERT INTO mc_revisions_editor (item_type, item_id, id_lang, editor_id, content, date_register)
			  			VALUES (:item_type, :item_id, :id_lang, :field, :content, NOW())';
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
            return false;
        }
    }

    /**
     * @param string $type
     * @param array $params
     * @return bool|string
     */
    public function delete(string $type, array $params = []) {
        switch ($type) {
            case 'delRevisions':
                $query = 'DELETE FROM mc_revisions_editor 
                    WHERE item_type = :item_type 
                      AND item_id = :item_id 
                      AND id_lang = :id_lang 
                      AND editor_id = :field
                      AND id NOT IN (
                          SELECT id FROM (
                              SELECT id FROM mc_revisions_editor 
                              WHERE item_type = :item_type 
                                AND item_id = :item_id 
                                AND id_lang = :id_lang 
                                AND editor_id = :field
                              ORDER BY date_register DESC 
                              LIMIT 10
                          ) tmp
                      )';
                break;
            case 'clearFullHistory':
                $query = 'DELETE FROM mc_revisions_editor 
                    WHERE item_type = :item_type 
                      AND item_id = :item_id 
                      AND id_lang = :id_lang 
                      AND editor_id = :field';
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
?>