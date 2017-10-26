<?php
class frontend_db_about
{
	/**
	 * @param $config
	 * @param bool $data
	 * @return mixed|null
	 */
	public function fetchData($config,$data = false)
	{
		$sql = '';
		$params = false;
		$dateFormat = new component_format_date();

		if(is_array($config)) {
			if($config['context'] === 'all') {
				if ($config['type'] === 'root') {
					$sql = 'SELECT d.name_info,d.value_info 
                            FROM mc_about_data AS d
                            JOIN mc_lang AS lang ON(d.id_lang = lang.id_lang)
                            WHERE lang.iso_lang = :iso';
					$params = $data;
				}
				elseif ($config['type'] === 'pages') {
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$sql = "SELECT
                    		p.*,c.*,lang.iso_lang, lang.default_lang
                    		FROM mc_about_page AS p
                    		JOIN mc_about_page_content AS c ON(p.id_pages = c.id_pages) 
                    		JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    $conditions";

					$params = $data;
				}
				elseif($config['type'] === 'child'){
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

					$sql = "SELECT p.id_pages,p.id_parent,p.img_pages,p.menu_pages, p.date_register, c.*,lang.iso_lang
                    FROM mc_cms_page AS p
                        JOIN mc_about_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        $conditions";

					$params = $data;
				}
				elseif ($config['type'] === 'info') {
					$sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
				}
				elseif ($config['type'] === 'content') {
					$sql = 'SELECT a.*
                    		FROM mc_about_data AS a
                    		JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
				}
				elseif ($config['type'] === 'languages') {
					$sql = "SELECT `name_lang` FROM `mc_lang`";
				}
				elseif ($config['type'] === 'op') {
					$sql = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
				}
				elseif ($config['type'] === 'langs') {
					$sql = 'SELECT
							h.id_pages,c.url_pages,c.id_lang,lang.iso_lang
							FROM mc_about_page AS h
							JOIN mc_about_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND c.published_pages = 1';
					$params = $data;
				}

				return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
			}
			elseif($config['context'] === 'one') {
				if ($config['type'] === 'page') {
					$sql = 'SELECT
							h.*,c.*,lang.iso_lang
							FROM mc_about_page AS h
							JOIN mc_about_page_content AS c ON(h.id_pages = c.id_pages) 
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