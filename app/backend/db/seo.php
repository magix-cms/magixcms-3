<?php
class backend_db_seo
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'seo') {
                    $sql = "SELECT s.*, c.content_seo 
                    FROM mc_seo AS s
                    JOIN mc_seo_content AS c USING ( id_seo )
                    JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                    WHERE c.id_lang = :default_lang
                    GROUP BY s.id_seo";
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
        }
    }
}
?>