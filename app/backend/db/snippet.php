<?php
class backend_db_snippet
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';
        $dateFormat = new component_format_date();

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

                    $sql = "SELECT 
								st.*
							FROM mc_snippet AS st
							ORDER BY st.id_snippet DESC".$limit;
                    break;
            }

            return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;

        }elseif ($config['context'] === 'one') {
            switch ($config['type']) {
                case 'page':
                    $sql = 'SELECT 
								st.*
							FROM mc_snippet AS st
							WHERE st.id_snippet = :id';
                    break;
            }
            return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function insert($config,$params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {
            case 'page':
                $sql = "INSERT INTO `mc_snippet`(title_sp, description_sp, content_sp, date_register) 
                        VALUE (:title_sp, :description_sp, :content_sp, NOW())";
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
    public function update($config,$params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {
            case 'page':
                $sql = 'UPDATE mc_snippet 
							SET 
								title_sp=:title_sp, 
							    description_sp=:description_sp, 
							    content_sp=:content_sp

							WHERE id_snippet = :id_snippet';
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
            case 'delPages':
                $sql = 'DELETE FROM mc_snippet 
						WHERE id_snippet IN ('.$params['id'].')';
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