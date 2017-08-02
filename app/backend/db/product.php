<?php
class backend_db_product{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;
        $dateFormat = new component_format_date();
        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'pages') {
                    $sql = "SELECT p.id_p, c.name_p, c.content_p, p.date_register, p.img_p
								FROM mc_catalog_product AS p
									JOIN mc_catalog_product_content AS c USING ( id_p )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang
									GROUP BY p.id_p 
								ORDER BY p.id_p";
                    if (isset($config['search'])) {
                        $cond = '';
                        $config['search'] = array_filter($config['search']);
                        if (is_array($config['search']) && !empty($config['search'])) {
                            $nbc = 0;
                            foreach ($config['search'] as $key => $q) {
                                if ($q != '') {
                                    $cond .= 'AND ';
                                    switch ($key) {
                                        case 'id_p':
                                        case 'published_p':
                                            $cond .= 'c.' . $key . ' = ' . $q . ' ';
                                            break;
                                        case 'name_p':
                                            $cond .= "c." . $key . " LIKE '%" . $q . "%' ";
                                            break;
                                        case 'date_register':
                                            $q = $dateFormat->date_to_db_format($q);
                                            $cond .= "p." . $key . " LIKE '%" . $q . "%' ";
                                            break;
                                    }
                                    $nbc++;
                                }
                            }

                            $sql = "SELECT p.id_p, c.name_p,c.content_p, p.date_register, p.img_p
								FROM mc_catalog_product AS p
									JOIN mc_catalog_product_content AS c USING ( id_p )
									JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
									WHERE c.id_lang = :default_lang $cond
									GROUP BY p.id_p 
								ORDER BY p.id_p";
                        }
                    }
                    $params = $data;
                }
            }
        }
    }
}
?>