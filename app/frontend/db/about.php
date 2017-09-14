<?php
class frontend_db_about
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
				if ($config['type'] === 'info') {
					$sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
				}
				elseif ($config['type'] === 'content') {
					$sql = 'SELECT a.*
                    		FROM mc_about_data AS a
                    		JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
				}
				elseif ($config['type'] === 'languages') {
					$sql = "SELECT `name_lang` FROM `mc_lang`";
				}
				elseif ($config['type'] === 'op') {
					$sql = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
				}

				return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
			}
			/*elseif($config['context'] === 'one') {

				return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
			}*/
		}
	}
}
?>