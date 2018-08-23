<?php
class backend_db_home{
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
				case 'pages':
					$sql = 'SELECT h.*,c.*
							FROM mc_home_page AS h
							JOIN mc_home_page_content AS c USING(id_page)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$sql = 'SELECT * FROM mc_home_page ORDER BY id_page DESC LIMIT 0,1';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_home_page_content` WHERE `id_page` = :id_page AND `id_lang` = :id_lang';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
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
			case 'newHome':
				$sql = 'INSERT INTO `mc_home_page`(`date_register`) VALUES (NOW())';
				break;
			case 'newContent':
				$sql = 'INSERT INTO `mc_home_page_content`(id_page,id_lang,title_page,content_page,seo_title_page,seo_desc_page,published) 
				  		VALUES (:id_page,:id_lang,:title_page,:content_page,:seo_title_page,:seo_desc_page,:published)';
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
				$sql = 'UPDATE mc_home_page_content SET title_page = :title_page, content_page=:content_page, seo_title_page=:seo_title_page, seo_desc_page=:seo_desc_page, published=:published
                		WHERE id_page = :id_page AND id_lang = :id_lang';
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
}