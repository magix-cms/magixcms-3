<?php
class plugins_contact_db
{
	/**
	 * @param $config
	 * @param bool $data
	 * @return mixed|null
	 */
    public function fetchData($config, $data = false)
    {
        $sql = '';
        $params = false;

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                if ($config['type'] === 'contact') {
                    $sql = 'SELECT p.*,c.*,lang.*
                            FROM mc_contact AS p
                            JOIN mc_contact_content AS c USING(id_contact)
                            JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                            WHERE c.id_lang = :default_lang
							GROUP BY p.id_contact';
                    $params = $data;
                }
                elseif ($config['type'] === 'contacts') {
                    $sql = 'SELECT p.id_contact, p.mail_contact
                            FROM mc_contact AS p
                            JOIN mc_contact_content AS c USING(id_contact)
                            JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                            WHERE lang.iso_lang = :lang
							GROUP BY p.id_contact';
                    $params = $data;
                }
                elseif ($config['type'] === 'data') {
                    $sql = 'SELECT p.*,c.*,lang.*
                            FROM mc_contact AS p
                            JOIN mc_contact_content AS c USING(id_contact)
                            JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                            WHERE p.id_contact = :edit';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            }
            elseif ($config['context'] === 'one') {
                if ($config['type'] === 'root') {
                    //Return current skin
                    $sql = 'SELECT * FROM mc_contact ORDER BY id_contact DESC LIMIT 0,1';
                    //$params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_contact_content` WHERE `id_contact` = :id_contact AND `id_lang` = :id_lang';
                    $params = $data;
                }
                elseif ($config['type'] === 'config') {
                    //Return current skin
                    $sql = 'SELECT * FROM mc_contact_config ORDER BY id_config DESC LIMIT 0,1';
                    //$params = $data;
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
            $sql = '';
            $params = $data;

            if ($config['type'] === 'contact') {
                $sql = 'INSERT INTO mc_contact (mail_contact)
                VALUE (:mail_contact)';
            }
            elseif ($config['type'] === 'content') {
                $sql = 'INSERT INTO `mc_contact_content`(id_contact,id_lang,published_contact) 
				  		VALUES (:id_contact,:id_lang,:published_contact)';
            }
            elseif ($config['type'] === 'config') {
                $sql = 'INSERT INTO `mc_contact_config`(address_enabled,address_required) 
				  		VALUES (:address_enabled,:address_required)';
            }

            if($sql && $params) component_routing_db::layer()->insert($sql,$params);
        }
    }

    /**
     * @param $config
     * @param bool $data
     */
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            $sql = '';
            $params = $data;

            if ($config['type'] === 'contact') {
                $sql = 'UPDATE mc_contact 
							SET 
								mail_contact = :mail_contact
							WHERE id_contact = :id_contact';
            }
            elseif ($config['type'] === 'content') {
                $sql = 'UPDATE mc_contact_content 
						SET 
							published_contact=:published_contact
                		WHERE id_contact = :id_contact 
                		AND id_lang = :id_lang';
            }
            elseif ($config['type'] === 'config') {
                $sql = 'UPDATE mc_contact_config 
						SET 
							address_enabled=:address_enabled,
							address_required=:address_required
                		WHERE id_config = :id_config';
            }

            if($sql && $params) component_routing_db::layer()->update($sql,$params);
        }
    }
    /**
     * @param $config
     * @param bool $data
     */
    public function delete($config,$data = false)
    {
        if (is_array($config)) {
            if($config['type'] === 'delMail'){
                $sql = 'DELETE FROM mc_contact WHERE id_contact IN ('.$data['id'].')';
                component_routing_db::layer()->delete($sql,array());
            }
        }
    }
}