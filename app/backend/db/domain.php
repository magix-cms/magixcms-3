<?php
class backend_db_domain
{
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'domain') {
                    //Listing employee
                    $cond = '';
                    if (isset($config['search']) && is_array($config['search']) && !empty($config['search'])) {
                        $nbc = 0;
                        $params = array();
                        foreach ($config['search'] as $key => $q) {
                            if ($q != '') {
                                if ($nbc > 0) {
                                    $cond .= 'AND ';
                                } else {
                                    $cond = 'WHERE ';
                                }
								$params[$key] = $q;
                                switch ($key) {
                                    case 'id_domain':
                                        $cond .= 'd.' . $key . ' = :' . $key . ' ';
                                        break;
                                    case 'url_domain':
                                        $cond .= "d." . $key . " LIKE '%:" . $key . "%' ";
                                        break;
                                }
                                $nbc++;
                            }
                        }
                    }
                    $sql = "SELECT d.* 
                    FROM mc_domain AS d $cond";
                    //$params = $data;
                }
                elseif ($config['type'] === 'langs') {
                    $sql = 'SELECT dl.*,lang.iso_lang, lang.name_lang
                            FROM mc_domain_language AS dl
                            JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
                            WHERE id_domain = :id';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'domain') {
                    $sql = 'SELECT * FROM mc_domain WHERE id_domain = :id';
                    $params = $data;
                }
                elseif ($config['type'] === 'count') {
                    $sql = 'SELECT count(id_domain) AS nb FROM mc_domain';
                }
                elseif ($config['type'] === 'lastLanguage') {
                    $sql = 'SELECT dl.*,lang.iso_lang, lang.name_lang
                            FROM mc_domain_language AS dl
                            JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
                            WHERE dl.id_domain = :id
                            ORDER BY dl.id_domain_lg DESC LIMIT 0,1';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }
    /**
     * @param $config
     * @param bool $data
     */
    public function insert($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'newDomain') {
                $sql = 'INSERT INTO mc_domain (url_domain,default_domain)
                VALUE (:url_domain,:default_domain)';
                component_routing_db::layer()->insert($sql,
                    array(
                        ':url_domain'	    => $data['url_domain'],
                        ':default_domain'	=> $data['default_domain']
                    )
                );
            }elseif ($config['type'] === 'newLanguage') {
                $sql = 'INSERT INTO `mc_domain_language` (id_domain,id_lang,default_lang)
						VALUES (:id_domain,:id_lang,:default_lang)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_domain'	=> $data['id_domain'],
                    ':id_lang'	    => $data['id_lang'],
                    ':default_lang'	=> $data['default_lang']
                ));
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     * @throws Exception
     */
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'domain') {
                $sql = 'UPDATE mc_domain SET url_domain = :url_domain,tracking_domain = :tracking_domain, 
                default_domain=:default_domain 
                WHERE id_domain = :id_domain';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_domain'	    => $data['id_domain'],
                        ':url_domain'	    => $data['url_domain'],
                        ':tracking_domain'	=> $data['tracking_domain'],
                        ':default_domain'	=> $data['default_domain']
                    )
                );
            }elseif($config['type'] === 'modules'){
                $query = "UPDATE `mc_config`
					SET `status` = CASE `attr_name`
						WHEN 'pages' THEN :pages
						WHEN 'news' THEN :news
						WHEN 'catalog' THEN :catalog
						WHEN 'about' THEN :about
					END
					WHERE `attr_name` IN ('pages','news','catalog','about')";

                component_routing_db::layer()->update($query,
                    array(
                        ':pages'	=> $data['pages'],
                        ':news'	    => $data['news'],
                        ':catalog'	=> $data['catalog'],
                        ':about'	=> $data['about']
                    )
                );
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     * @throws Exception
     */
    public function delete($config,$data = false)
    {
        if (is_array($config)) {
            if($config['type'] === 'delDomain'){
                $sql = 'DELETE FROM mc_domain WHERE id_domain IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }elseif($config['type'] === 'delLanguage') {
                $sql = 'DELETE FROM mc_domain_language WHERE id_domain_lg IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}