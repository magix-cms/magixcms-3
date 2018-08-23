<?php
class frontend_db_share
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

		if($config['context'] === 'all') {
			switch ($config['type']) {
			    case 'shareUrl':
			    	$sql = 'SELECT * FROM mc_share_url';
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
			    case 'shareConfig':
			    	$sql = 'SELECT * FROM mc_share_config WHERE id_share = :id';
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
	}
}