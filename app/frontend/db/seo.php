<?php
class frontend_db_seo
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
			switch ($config['type']) {
			    case 'seo':
					$sql = "SELECT s.*, c.content_seo 
						FROM mc_seo AS s
						JOIN mc_seo_content AS c USING ( id_seo )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE c.id_lang = :default_lang
						GROUP BY s.id_seo";
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
			    case 'replace':
					$sql = 'SELECT * 
						FROM mc_seo 
						JOIN mc_seo_content USING(id_seo)
						LEFT JOIN mc_lang USING(id_lang)
						WHERE iso_lang = :iso
						AND level_seo = :lvl
						AND attribute_seo = :attribute
						AND type_seo = :type
						ORDER BY id_seo 
						DESC LIMIT 0,1';
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
    }
}