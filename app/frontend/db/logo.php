<?php
class frontend_db_logo
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

                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                switch ($config['type']) {
                    case 'page':
                        $sql = 'SELECT p.img_logo,p.active_logo,c.alt_logo,c.title_logo,lang.iso_lang,lang.id_lang
							FROM mc_logo AS p
							JOIN mc_logo_content AS c USING(id_logo)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE lang.iso_lang = :iso LIMIT 0,1';
                        break;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }
}