<?php
class backend_db_about
{

    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'info') {
                    $sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
                }
                elseif($config['type'] === 'content') {
                    $sql = 'SELECT a.*
                    		FROM mc_about_data AS a
                    		JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';

                }
                elseif($config['type'] === 'op') {
                    $sql = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
                }
                elseif($config['type'] == 'languages') {
                	$sql = "SELECT `name_lang` FROM `mc_lang`";
				}
                elseif($config['type'] == 'iso') {
                	$sql = "SELECT `iso_lang` FROM `mc_lang`";
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'info') {
                    //Return current skin
                    $sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
                    //$params = $data;
                }
                elseif ($config['type'] === 'content') {
                    $sql = 'SELECT * FROM `mc_about_data` WHERE `id_lang` = :id_lang';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
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
            if ($config['type'] === 'newContent') {
                $queries = array(
                    array(
                        'request'=>"INSERT INTO `mc_about_data` (`id_lang`,`name_info`,`value_info`)
				                    VALUE(:id_lang,'desc',:dsc)",
                        'params'=>array(':id_lang' => $data['id_lang'],':dsc' => $data['desc'])
                    ),
                    array(
                        'request'=>"INSERT INTO `mc_about_data` (`id_lang`,`name_info`,`value_info`)
				                    VALUE(:id_lang,'slogan',:slogan)",
                        'params'=>array(':id_lang' => $data['id_lang'],':slogan' => $data['slogan'])
                    ),
                    array(
                        'request'=>"INSERT INTO `mc_about_data` (`id_lang`,`name_info`,`value_info`)
				                    VALUE(:id_lang,'content',:content)",
                        'params'=>array(':id_lang' => $data['id_lang'],':content' => $data['content'])
                    )
                );
                component_routing_db::layer()->transaction($queries);
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
            if ($config['type'] === 'company') {

                // Update company Data
                $query = "UPDATE `mc_about`
					SET `value_info` = CASE `name_info`
						WHEN 'name' THEN :nme
						WHEN 'type' THEN :tpe
						WHEN 'eshop' THEN :eshop
						WHEN 'tva' THEN :tva
					END
					WHERE `name_info` IN ('name','type','eshop','tva')";

                component_routing_db::layer()->update($query,
                    array(
                        ':nme' 		=> $data['name'],
                        ':tpe' 		=> $data['type'],
                        ':eshop' 	=> $data['eshop'],
                        ':tva' 		=> $data['tva']
                    )
                );
            }
            elseif ($config['type'] === 'contact') {
                // Update contact Data
                $query = "UPDATE `mc_about`
					SET `value_info` = CASE `name_info`
						WHEN 'mail' THEN :mail
						WHEN 'click_to_mail' THEN :click_to_mail
						WHEN 'crypt_mail' THEN :crypt_mail
						WHEN 'phone' THEN :phone
						WHEN 'mobile' THEN :mobile
						WHEN 'click_to_call' THEN :click_to_call
						WHEN 'fax' THEN :fax
						WHEN 'adress' THEN :adress
						WHEN 'street' THEN :street
						WHEN 'postcode' THEN :postcode
						WHEN 'city' THEN :city
					END
					WHERE `name_info` IN ('mail','click_to_mail','crypt_mail','phone','mobile','click_to_call','fax','adress','street','postcode','city')";

                component_routing_db::layer()->update($query,
                    array(
                        ':mail' 			=> $data['contact']['mail'],
                        ':click_to_mail'	=> $data['contact']['click_to_mail'],
                        ':crypt_mail'		=> $data['contact']['crypt_mail'],
                        ':phone' 			=> $data['contact']['phone'],
                        ':mobile' 			=> $data['contact']['mobile'],
                        ':click_to_call'	=> $data['contact']['click_to_call'],
                        ':fax' 				=> $data['contact']['fax'],
                        ':adress' 			=> $data['contact']['adress']['adress'],
                        ':street' 			=> $data['contact']['adress']['street'],
                        ':postcode' 		=> $data['contact']['adress']['postcode'],
                        ':city' 			=> $data['contact']['adress']['city'],
                    )
                );

            }
            elseif ($config['type'] === 'languages') {
                // Update contact Data
                $sql = "UPDATE `mc_about` SET `value_info` = :languages WHERE `name_info` = 'languages'";

                component_routing_db::layer()->update($sql,
					array(
						':languages' => $data['languages']
					)
                );

            }
            elseif ($config['type'] === 'socials') {
                // Update socials Data

                $query = "UPDATE `mc_about`
					SET `value_info` = CASE `name_info`
						WHEN 'facebook' THEN :facebook
						WHEN 'twitter' THEN :twitter
						WHEN 'google' THEN :google
						WHEN 'linkedin' THEN :linkedin
						WHEN 'viadeo' THEN :viadeo
					END
					WHERE `name_info` IN ('facebook','twitter','google','linkedin','viadeo')";

                component_routing_db::layer()->update($query,
                    array(
                        ':facebook' => $data['socials']['facebook'],
                        ':twitter'	=> $data['socials']['twitter'],
                        ':google' 	=> $data['socials']['google'],
                        ':linkedin' => $data['socials']['linkedin'],
                        ':viadeo' 	=> $data['socials']['viadeo']
                    )
                );

            }
            elseif ($config['type'] === 'content') {

                // Update text (root) Data
                $sql = "UPDATE `mc_about_data`
					SET `value_info` = CASE `name_info`
						WHEN 'desc' THEN :dsc
						WHEN 'slogan' THEN :slogan
						WHEN 'content' THEN :content
					END
					WHERE `name_info` IN ('desc','slogan','content') AND id_lang = :id_lang";

                component_routing_db::layer()->update($sql,
                    array(
                        ':dsc' 		=> $data['desc'],
                        ':slogan' 	=> $data['slogan'],
                        ':content' 	=> $data['content'],
                        ':id_lang' 	=> $data['id_lang']
                    )
                );
            }
            elseif ($config['type'] === 'enable_op') {

                $query = "UPDATE mc_about SET value_info = :enable WHERE name_info = 'openinghours'";

                component_routing_db::layer()->update($query,
                    array(
                        ':enable' => $data['enable_op']
                    )
                );

            }
            elseif ($config['type'] === 'openinghours') {

                foreach ($data['specifications'] as $day => $opt) {
                    $query = "UPDATE `mc_about_op`
					SET `open_day` = :open_day,
					`noon_time` = CASE `open_day`
									WHEN '1' THEN :noon_time
									ELSE `noon_time`
									END,
					`open_time` = CASE `open_day`
									WHEN '1' THEN :open_time
									ELSE `open_time`
									END,
					`close_time` = CASE `open_day`
									WHEN '1' THEN :close_time
									ELSE `close_time`
									END,
					`noon_start` = CASE `open_day`
									WHEN '1' THEN
									 	CASE `noon_time`
									 	WHEN '1' THEN :noon_start
										ELSE `noon_start`
										END
									ELSE `noon_start`
									END,
					`noon_end` = CASE `open_day`
									WHEN '1' THEN
									 	CASE `noon_time`
									 	WHEN '1' THEN :noon_end
										ELSE `noon_end`
										END
									ELSE `noon_end`
									END
					WHERE `day_abbr` = :cday";

                    component_routing_db::layer()->update($query,array(
                        ':cday'			=> $day,
                        ':open_day' 	=> $opt['open_day'],
                        ':noon_time' 	=> $opt['noon_time'],
                        ':open_time' 	=> $opt['open_time'],
                        ':close_time' 	=> $opt['close_time'],
                        ':noon_start' 	=> $opt['noon_start'],
                        ':noon_end' 	=> $opt['noon_end'],
                    ));
                }
            }
        }
    }
}
?>