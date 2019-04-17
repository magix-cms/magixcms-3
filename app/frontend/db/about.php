<?php
class frontend_db_about
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';
		$dateFormat = new component_format_date();

		if($config['context'] === 'all') {
			switch ($config['type']){
				case 'root':
					$sql = 'SELECT d.name_info,d.value_info 
							FROM mc_about_data AS d
							JOIN mc_lang AS lang ON(d.id_lang = lang.id_lang)
							WHERE lang.iso_lang = :iso';
					break;
				case 'pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT
								p.*,
								c.name_pages,
								c.url_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
       							COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
								lang.iso_lang,
								lang.default_lang
							FROM mc_about_page AS p
							JOIN mc_about_page_content AS c ON(p.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							$conditions";
					break;
				case 'parents':
					$sql = "SELECT t.id_pages AS parent, GROUP_CONCAT(f.id_pages) AS children
								FROM mc_about_page t
								JOIN mc_about_page f ON t.id_pages=f.id_parent
								GROUP BY t.id_pages";
					break;
				case 'child':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$sql = "SELECT
								p.id_pages,
       							p.id_parent,
       							p.img_pages,
       							p.menu_pages,
       							p.date_register, 
      							c.name_pages,
								c.url_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
       							COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
    							lang.iso_lang
							FROM mc_cms_page AS p
							JOIN mc_about_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
							LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
							$conditions";
					break;
				case 'info':
					$sql = "SELECT a.name_info,a.value_info FROM mc_about AS a";
					break;
				case 'content':
					$sql = 'SELECT a.*
							FROM mc_about_data AS a
							JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
					break;
				case 'languages':
					$sql = "SELECT `name_lang` FROM `mc_lang`";
					break;
				case 'op':
					$sql = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
					break;
				case 'op_content':
					$sql = "SELECT * FROM `mc_about_op_content` 
							JOIN mc_lang AS lang USING(id_lang)";
					break;
				case 'langs':
					$sql = 'SELECT
							h.id_pages,c.url_pages,c.id_lang,lang.iso_lang
							FROM mc_about_page AS h
							JOIN mc_about_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND c.published_pages = 1';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']){
				case 'page':
					$sql = 'SELECT
								h.*,
								c.name_pages,
								c.url_pages,
								c.resume_pages,
								c.content_pages,
								c.published_pages,
       							COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								COALESCE(c.seo_desc_pages, c.resume_pages) as seo_desc_pages,
								lang.iso_lang
							FROM mc_about_page AS h
							JOIN mc_about_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND lang.iso_lang = :iso AND c.published_pages = 1';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
	}
}