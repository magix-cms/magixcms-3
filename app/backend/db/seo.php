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
                }elseif ($config['type'] === 'editSeo') {
                    $sql = "SELECT s.*, c.content_seo, c.id_lang 
                    FROM mc_seo AS s
                    JOIN mc_seo_content AS c USING ( id_seo )
                    JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                    WHERE s.id_seo = :edit";
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }elseif ($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'root') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_seo ORDER BY id_seo DESC LIMIT 0,1';
                    //$params = $data;
                }elseif ($config['type'] === 'seo') {
                    //Return current row
                    $sql = 'SELECT * FROM mc_seo WHERE `id_seo` = :id_seo';
                    $params = $data;
                }elseif ($config['type'] === 'content') {

                    $sql = 'SELECT * FROM `mc_seo_content` WHERE `id_seo` = :id_seo AND `id_lang` = :id_lang';
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
            if ($config['type'] === 'newSeo') {
                $sql = 'INSERT INTO `mc_seo`(level_seo,attribute_seo,type_seo) 
              VALUES (:level_seo,:attribute_seo,:type_seo)';
                component_routing_db::layer()->insert($sql, array(
                    ':level_seo'        => $data['level_seo'],
                    ':attribute_seo'    => $data['attribute_seo'],
                    ':type_seo'         => $data['type_seo']
                ));
            }elseif ($config['type'] === 'newContent') {

                $sql = 'INSERT INTO `mc_seo_content`(id_seo,id_lang,content_seo) 
				  VALUES (:id_seo,:id_lang,:content_seo)';

                component_routing_db::layer()->insert($sql,array(
                    ':id_lang'	       => $data['id_lang'],
                    ':id_seo'	       => $data['id_seo'],
                    ':content_seo'       => $data['content_seo']
                ));

            }
        }
    }
    /**
     * @param $config
     * @param bool $data
     */
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'content') {
                $sql = 'UPDATE mc_seo_content SET content_seo=:content_seo
                WHERE id_seo = :id_seo AND id_lang = :id_lang';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_lang'      => $data['id_lang'],
                        ':id_seo'       => $data['id_seo'],
                        ':content_seo'  => $data['content_seo']
                    )
                );
            }elseif ($config['type'] === 'data') {
                $sql = 'UPDATE mc_seo SET level_seo=:level_seo,attribute_seo=:attribute_seo,type_seo=:type_seo
                WHERE id_seo = :id_seo';
                component_routing_db::layer()->update($sql,
                    array(
                        ':id_seo'           => $data['id_seo'],
                        ':level_seo'        => $data['level_seo'],
                        ':attribute_seo'    => $data['attribute_seo'],
                        ':type_seo'         => $data['type_seo']
                    )
                );
            }
        }
    }
    /**
     * @param $config
     * @param bool $data
     */
    public function delete($config,$data = false)
    {
        if (is_array($config)) {
            if($config['type'] === 'delSeo'){
                $sql = 'DELETE FROM mc_seo WHERE id_seo IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}
?>