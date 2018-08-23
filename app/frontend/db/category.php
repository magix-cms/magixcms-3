<?php
class frontend_db_category
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
				case 'root':
					$sql = 'SELECT * FROM mc_catalog_cat ORDER BY id_cat DESC LIMIT 0,1';
					break;
				case 'content':
					$sql = 'SELECT * FROM `mc_catalog_cat_content` WHERE `id_cat` = :id_cat AND `id_lang` = :id_lang';
					break;
				case 'wsEdit':
					$sql = 'SELECT * FROM mc_catalog_cat WHERE `id_cat` = :id';
					break;
				case 'image':
					$sql = 'SELECT img_cat FROM mc_catalog_cat WHERE `id_cat` = :id_cat';
					break;
				case 'page':
					$sql = 'SELECT * FROM mc_catalog_cat WHERE `id_cat` = :id_cat';
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
			case 'page':
				$cond = $params['id_parent'] != NULL ? 'IN ('.$params['id_parent'].')' : 'IS NULL' ;
				$sql = "INSERT INTO `mc_catalog_cat`(id_parent,order_cat,date_register) 
						SELECT :id_parent,COUNT(id_cat),NOW() FROM mc_catalog_cat WHERE id_parent $cond";
				break;
			case 'content':
				$sql = 'INSERT INTO `mc_catalog_cat_content`(id_cat,id_lang,name_cat,url_cat,resume_cat,content_cat,published_cat) 
			  			VALUES (:id_cat,:id_lang,:name_cat,:url_cat,:resume_cat,:content_cat,:published_cat)';
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
			case 'page':
				$sql = 'UPDATE mc_catalog_cat 
						SET 
							id_parent = :id_parent,
							menu_cat = :menu_cat
						WHERE id_cat = :id_cat';
				break;
			case 'content':
				$sql = 'UPDATE mc_catalog_cat_content 
						SET 
							name_cat = :name_cat, 
							url_cat = :url_cat, 
							resume_cat = :resume_cat, 
							content_cat = :content_cat, 
							published_cat = :published_cat
						WHERE id_cat = :id_cat AND id_lang = :id_lang';
				break;
			case 'img':
				$sql = 'UPDATE mc_catalog_cat SET img_cat = :img_cat WHERE id_cat = :id_cat';
				break;
			case 'order':
				$sql = 'UPDATE mc_catalog_cat SET order_cat = :order_cat WHERE id_cat = :id_cat';
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
				$sql = 'DELETE FROM `mc_catalog_cat` WHERE `id_cat` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'delProduct':
				$sql = 'DELETE FROM `mc_catalog` WHERE `id_catalog` IN ('.$params['id'].')';
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