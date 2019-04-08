<?php
class backend_db_logo
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
                case 'page':
                    $sql = 'SELECT p.*,c.*,lang.*
							FROM mc_logo AS p
							JOIN mc_logo_content AS c USING(id_logo)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_logo = :edit';
                    break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
                case 'root':
                    $sql = 'SELECT * FROM mc_logo ORDER BY id_logo DESC LIMIT 0,1';
                    break;
                case 'content':
                    $sql = 'SELECT * FROM `mc_logo_content` WHERE `id_logo` = :id_logo AND `id_lang` = :id_lang';
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
            case 'img':
                $sql = "INSERT INTO `mc_logo`(img_logo,active_logo,date_register) 
						VALUES (:img_logo,:active_logo,NOW())";
                break;
            case 'imgContent':
                $sql = 'INSERT INTO `mc_logo_content`(id_logo,id_lang,alt_logo,title_logo,last_update) 
				  		VALUES (:id_logo,:id_lang,:alt_logo,:title_logo,NOW())';
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
            case 'img':
                $sql = 'UPDATE mc_logo
						SET img_logo = :img_logo,
						    active_logo = :active_logo
                		WHERE id_logo = :id_logo';
                break;
            case 'active':
                $sql = 'UPDATE mc_logo
						SET active_logo = :active_logo
                		WHERE id_logo = :id_logo';
                break;
            case 'imgContent':
                $sql = 'UPDATE mc_logo_content 
						SET 
							alt_logo = :alt_logo,
							title_logo = :title_logo
                		WHERE id_logo = :id_logo 
                		AND id_lang = :id_lang';
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