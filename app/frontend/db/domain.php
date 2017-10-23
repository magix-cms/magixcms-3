<?php
class frontend_db_domain
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'domain') {
                    $sql = "SELECT d.* FROM mc_domain AS d";
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'currentDomain') {
                    $sql = 'SELECT d.*
                            FROM mc_domain AS d
                            WHERE d.url_domain = :url';
                    $params = $data;
                }
                elseif ($config['type'] === 'language') {
                    $sql = 'SELECT dl.*,lang.iso_lang, lang.name_lang
                            FROM mc_domain_language AS dl
                            JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
                            WHERE dl.id_domain = :id AND dl.default_lang = 1';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }
}