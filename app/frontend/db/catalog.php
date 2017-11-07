<?php
class frontend_db_catalog
{
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if($config['type'] === 'root'){
                    $sql = 'SELECT d.name_info,d.value_info 
                            FROM mc_catalog_data AS d
                            JOIN mc_lang AS lang ON(d.id_lang = lang.id_lang)
                            WHERE lang.iso_lang = :iso';
                    $params = $data;
                }
                elseif ($config['type'] === 'images') {
                    $sql = 'SELECT img.*,c.alt_img,c.title_img,c.id_lang,lang.iso_lang
                        FROM mc_catalog_product_img AS img
                        LEFT JOIN mc_catalog_product_img_content AS c ON (img.id_img = c.id_img)
                        LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE img.id_product = :id AND (lang.iso_lang = :iso OR lang.iso_lang IS NULL)
                        ORDER BY img.order_img ASC';
                    $params = $data;
                }
                elseif ($config['type'] === 'catLang') {
                    $sql = 'SELECT
                    h.id_parent,h.id_cat,c.id_lang,c.name_cat,c.url_cat,lang.iso_lang
                    FROM mc_catalog_cat AS h
                    JOIN mc_catalog_cat_content AS c ON(h.id_cat = c.id_cat) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    WHERE h.id_cat = :id AND c.published_cat = 1';
                    $params = $data;
                }
                elseif ($config['type'] === 'productLang') {
                    $sql = 'SELECT c.* ,cat.name_cat, cat.url_cat, p.*, pc.name_p, pc.url_p, pc.id_lang,lang.iso_lang, pc.last_update
                    FROM mc_catalog AS c
                    JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
                    JOIN mc_catalog_product AS p ON ( c.id_product = p.id_product )
                    JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
                    JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang )
                    WHERE p.id_product = :id AND cat.published_cat =1 AND pc.published_p =1';
                    $params = $data;
                }
                elseif ($config['type'] === 'product_ws') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT p.*,c.*,lang.*
                        FROM mc_catalog_product AS p
                        JOIN mc_catalog_product_content AS c USING(id_product)
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        $conditions";
                    $params = $data;
                }
                elseif ($config['type'] === 'product_similar_ws') {
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT rel.*,p.*,c.name_p, c.resume_p, c.content_p, c.url_p,lang.id_lang,lang.iso_lang,default_lang
                    FROM mc_catalog_product_rel AS rel
                    JOIN mc_catalog_product AS p ON ( rel.id_product_2 = p.id_product )
                    JOIN mc_catalog_product_content AS c ON(p.id_product = c.id_product)
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    $conditions";
                    $params = $data;
                }

                elseif ($config['type'] === 'images_ws') {
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                    /*$sql = 'SELECT img.*,c.alt_img,c.title_img,c.id_lang,lang.iso_lang
                        FROM mc_catalog_product_img AS img
                        LEFT JOIN mc_catalog_product_img_content AS c ON (img.id_img = c.id_img)
                        LEFT JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        WHERE img.id_product = :id';*/
                    $sql = "SELECT img.*
                        FROM mc_catalog_product_img AS img
                        $conditions";
                    $params = $data;
                }
                elseif ($config['type'] === 'images_content_ws') {
                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
                    $sql = "SELECT c.*,lang.iso_lang
                        FROM mc_catalog_product_img_content AS c
                        JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                        $conditions";
                    $params = $data;
                }
                elseif ($config['type'] === 'category') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT
                    p.*,c.*,lang.iso_lang
                    FROM mc_catalog_cat AS p
                    JOIN mc_catalog_cat_content AS c ON(p.id_cat = c.id_cat) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    $conditions";

                    $params = $data;

                }
                elseif ($config['type'] === 'product') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT catalog.* ,cat.name_cat, cat.url_cat, p.*, pc.name_p, pc.resume_p, pc.content_p, pc.url_p, pc.id_lang,lang.iso_lang, pc.last_update, img.name_img
                    		FROM mc_catalog AS catalog 
                    		JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
                    		JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
                    		JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
                    		JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
                    		LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
                    		JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang) 
                    $conditions";

                    $params = $data;

                }
                elseif ($config['type'] === 'similar'){
                    $sql = 'SELECT cat.name_cat, cat.url_cat, catalog.id_cat, p.*, pc.name_p, pc.resume_p, pc.url_p, pc.id_lang,lang.iso_lang, pc.last_update, img.name_img
                    FROM mc_catalog_product_rel AS rel
                    JOIN mc_catalog AS catalog ON (rel.id_product_2 = catalog.id_product)
                    JOIN mc_catalog_cat AS c ON ( catalog.id_cat = c.id_cat )
                    JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
                    JOIN mc_catalog_product AS p ON ( catalog.id_product = p.id_product )
                    JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
                    LEFT JOIN mc_catalog_product_img AS img ON (p.id_product = img.id_product)
                    JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang ) AND (cat.id_lang = lang.id_lang)
                    WHERE rel.id_product = :id AND lang.iso_lang = :iso AND catalog.default_c = 1 AND img.default_img = 1';

                    $params = $data;
                }

                /*elseif ($config['type'] === 'ws_cat') {
                    $sql = "SELECT
                    p.*,c.*,lang.iso_lang
                    FROM mc_catalog_cat AS p
                    JOIN mc_catalog_cat_content AS c ON(p.id_cat = c.id_cat)
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)";
                    $params = $data;
                }*/

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'cat') {
                    //Return current skin
                    $sql = 'SELECT p.*,c.*,lang.*
							FROM mc_catalog_cat AS p
							JOIN mc_catalog_cat_content AS c USING(id_cat)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE p.id_cat = :id AND lang.iso_lang = :iso AND c.published_cat = 1';
                    $params = $data;
                }
                elseif ($config['type'] === 'product') {
                    $sql = 'SELECT c.* ,cat.name_cat, cat.url_cat, p.*, pc.name_p, pc.resume_p, pc.content_p, pc.url_p, pc.id_lang,lang.iso_lang, pc.last_update
                    		FROM mc_catalog AS c
                    		JOIN mc_catalog_cat_content AS cat ON ( c.id_cat = cat.id_cat )
                    		JOIN mc_catalog_product AS p ON ( c.id_product = p.id_product )
                    		JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
                    		JOIN mc_lang AS lang ON ( pc.id_lang = lang.id_lang )
                    		WHERE p.id_product = :id AND c.default_c =1 AND cat.published_cat =1 AND pc.published_p =1 AND lang.iso_lang = :iso';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>