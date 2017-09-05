<?php
class frontend_db_pages
{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'langs') {
                    $sql = 'SELECT
                    h.*,c.*,lang.iso_lang
                    FROM mc_cms_page AS h
                    JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    WHERE h.id_pages = :id AND c.published_pages = 1';
                    $params = $data;
                }elseif ($config['type'] === 'pages') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT
                    p.*,c.*,lang.iso_lang
                    FROM mc_cms_page AS p
                    JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    $conditions";

                    $params = $data;

                    //WHERE lang.iso_lang = :iso AND c.published_pages = 1
                }elseif($config['type'] === 'child'){

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT p.id_pages,p.id_parent,p.img_pages,p.menu_pages, p.date_register, c.*,lang.iso_lang
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        $conditions";

                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params,array('debugParams'=>false)) : null;

            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'page') {
                    //Return current skin
                    $sql = 'SELECT
                    h.*,c.*,lang.iso_lang
                    FROM mc_cms_page AS h
                    JOIN mc_cms_page_content AS c ON(h.id_pages = c.id_pages) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    WHERE h.id_pages = :id AND lang.iso_lang = :iso AND c.published_pages = 1';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>