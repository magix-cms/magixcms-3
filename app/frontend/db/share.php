<?php
class frontend_db_share
{
	/**
	 * @param $config
	 * @param bool $data
	 * @return mixed|null
	 */
	public function fetchData($config,$data = false)
	{
		$sql = '';
		$params = false;
		$dateFormat = new component_format_date();

		if(is_array($config)) {
			if($config['context'] === 'all') {
				if($config['type'] === 'shareUrl') {
					$sql = 'SELECT * FROM mc_share_url';
				}

				return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
			}
			elseif($config['context'] === 'one') {
				if($config['type'] === 'shareConfig') {
					$sql = 'SELECT * FROM mc_share_config WHERE id_share = :id';

					$params = $data;
				}

				return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
			}
		}
	}
}
?>