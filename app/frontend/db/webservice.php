<?php
class frontend_db_webservice
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

            if ($config['context'] === 'all') {
            	//switch ($config['type']) {}

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
            	switch ($config['type']) {
            	    case 'auth':
						$sql = 'SELECT ws.* FROM mc_webservice AS ws LIMIT 1';
            	    	break;
            	}

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
    }
}