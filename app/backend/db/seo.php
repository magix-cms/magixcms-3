<?php
class backend_db_seo
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
				case 'editSeo':
					$sql = "SELECT s.*, c.content_seo, c.id_lang 
						FROM mc_seo AS s
						JOIN mc_seo_content AS c USING ( id_seo )
						JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
						WHERE s.id_seo = :edit";
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$sql = 'SELECT * FROM mc_seo ORDER BY id_seo DESC LIMIT 0,1';
					break;
				case 'seo':
					$sql = 'SELECT * FROM mc_seo WHERE `id_seo` = :id_seo';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_seo_content` WHERE `id_seo` = :id_seo AND `id_lang` = :id_lang';
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
	public function insert($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'newSeo':
				$sql = 'INSERT INTO `mc_seo`(level_seo,attribute_seo,type_seo) VALUES (:level_seo,:attribute_seo,:type_seo)';
				break;
			case 'newContent':
				$sql = 'INSERT INTO `mc_seo_content`(id_seo,id_lang,content_seo) VALUES (:id_seo,:id_lang,:content_seo)';
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
	public function update($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'content':
                $sql = 'UPDATE mc_seo_content SET content_seo = :content_seo
                WHERE id_seo = :id_seo AND id_lang = :id_lang';
            	break;
            case 'data':
                $sql = 'UPDATE mc_seo 
						SET 
							level_seo = :level_seo,
							attribute_seo = :attribute_seo,
							type_seo = :type_seo
                		WHERE id_seo = :id_seo';
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
			case 'page':
				$sql = 'DELETE FROM `mc_seo` WHERE `id_seo` IN ('.$params['id'].')';
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