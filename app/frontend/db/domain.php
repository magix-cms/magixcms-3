<?php
class frontend_db_domain
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

        if (is_array($config)) {
            if ($config['context'] === 'all') {
            	switch ($config['type']) {
					case 'domain':
						$sql = "SELECT d.* FROM mc_domain AS d";
						break;
					case 'languages':
						$sql = 'SELECT dl.id_lang,lang.iso_lang, lang.name_lang
                            FROM mc_domain_language AS dl
                            JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
                            WHERE dl.id_domain = :id
                            ORDER BY dl.default_lang DESC,dl.id_lang ASC';
						break;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
            	switch ($config['type']) {
					case 'currentDomain':
						$sql = 'SELECT d.* FROM mc_domain AS d WHERE d.url_domain = :url';
						break;
                    case 'defaultDomain':
                        $sql = 'SELECT d.* FROM mc_domain AS d WHERE d.default_domain = 1';
                        break;
					case 'language':
						$sql = 'SELECT dl.id_lang,lang.iso_lang, lang.name_lang
								FROM mc_domain_language AS dl
								JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
								WHERE dl.id_domain = :id AND dl.default_lang = 1';
						break;
				}

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }
}