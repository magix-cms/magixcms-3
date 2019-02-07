<?php
class backend_db_catalog{
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
				case 'content':
					$sql = 'SELECT a.*
							FROM mc_catalog_data AS a
							JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
					break;
				case 'category':
					$sql = 'SELECT cat.*,c.url_cat, c.id_lang,lang.iso_lang, c.last_update
							FROM mc_catalog_cat AS cat
							JOIN mc_catalog_cat_content AS c ON ( c.id_cat = cat.id_cat )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							WHERE c.published_cat =1 AND c.id_lang = :id_lang';
					break;
				case 'product':
					$sql = 'SELECT c.* , cat.url_cat, p.url_p, p.id_lang,lang.iso_lang, p.last_update
							FROM mc_catalog AS c
							JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
							JOIN mc_catalog_product_content AS p ON ( c.id_product = p.id_product )
							JOIN mc_lang AS lang ON ( p.id_lang = lang.id_lang )
							WHERE c.default_c =1 AND cat.published_cat =1 AND p.published_p =1 AND p.id_lang = :id_lang';
					break;
				case 'images':
					$sql = 'SELECT img.name_img
							FROM mc_catalog_product_img AS img
							WHERE img.id_product = :id';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'content':
					$sql = 'SELECT * FROM `mc_catalog_data` WHERE `id_lang` = :id_lang';
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


		if ($config['type'] === 'newContent') {
            $queries = array(
                array(
                    'request' => "SET @lang = :id_lang",
                    'params' => array('id_lang' => $params['id_lang'])
                ),
                array(
                    'request' => "INSERT INTO `mc_catalog_data` (`id_lang`,`name_info`,`value_info`) VALUES
							(@lang,'name',:nm),(@lang,'content',:content),(@lang,'seo_desc',:seo_desc),(@lang,'seo_title',:seo_title)",
                    'params' => array(
                        'nm'        => $params['name'],
                        'content'   => $params['content'],
                        'seo_desc'  => $params['seo_desc'],
                        'seo_title' => $params['seo_title']
                    )
                ),
            );

			try {
				component_routing_db::layer()->transaction($queries);
				return true;
			}
			catch (Exception $e) {
				return 'Exception reçue : '.$e->getMessage();
			}

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
				$sql = "UPDATE `mc_catalog_data`
                        SET `value_info` = CASE `name_info`
                            WHEN 'name' THEN :nm
                            WHEN 'content' THEN :content
                            WHEN 'seo_desc' THEN :seo_desc
						    WHEN 'seo_title' THEN :seo_title
                        END
                        WHERE `name_info` IN ('name','content','seo_desc','seo_title') AND id_lang = :id_lang";
				$params = array(
					'nm'        => $params['name'],
					'content'   => $params['content'],
                    'seo_title' => $params['seo_title'],
                    'seo_desc'  => $params['seo_desc'],
					'id_lang'   => $params['id_lang']
				);
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