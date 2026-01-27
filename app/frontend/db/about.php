<?php
class frontend_db_about {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []) {
		$dateFormat = new component_format_date();

		if($config['context'] === 'all') {
			switch ($config['type']){
				case 'root':
					$query = 'SELECT d.name_info,d.value_info 
							FROM mc_about_data AS d
							JOIN mc_lang AS lang ON(d.id_lang = lang.id_lang)
							WHERE lang.iso_lang = :iso';
					break;
				case 'pages':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT
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
				case 'pages_short':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT
								p.id_pages,
								p.id_parent,
								c.name_pages,
								c.url_pages,
       							COALESCE(c.seo_title_pages, c.name_pages) as seo_title_pages,
								lang.iso_lang
							FROM mc_about_page AS p
							JOIN mc_about_page_content AS c ON(p.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							$conditions";
					break;
				case 'parents':
					$query = "SELECT t.id_pages AS parent, GROUP_CONCAT(f.id_pages) AS children
								FROM mc_about_page t
								JOIN mc_about_page f ON t.id_pages=f.id_parent
								GROUP BY t.id_pages";
					break;
				case 'child':
					$config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';
					$query = "SELECT
								p.id_pages,
       							p.id_parent,
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
							FROM mc_about_page AS p
							JOIN mc_about_page_content AS c USING ( id_pages )
							JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
							LEFT JOIN mc_about_page AS pa ON ( p.id_parent = pa.id_pages )
							LEFT JOIN mc_about_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
							$conditions";
					break;
				case 'info':
					$query = "SELECT a.name_info,a.value_info FROM mc_about AS a";
					break;
				case 'content':
					$query = 'SELECT a.*
							FROM mc_about_data AS a
							JOIN mc_lang AS lang ON(a.id_lang = lang.id_lang)';
					break;
				case 'languages':
					$query = "SELECT `name_lang` FROM `mc_lang`";
					break;
				case 'op':
					$query = "SELECT `day_abbr`,`open_day`,`noon_time`,`open_time`,`close_time`,`noon_start`,`noon_end` FROM `mc_about_op`";
					break;
				case 'op_content':
					$query = "SELECT * FROM `mc_about_op_content` 
							JOIN mc_lang AS lang USING(id_lang)";
					break;
				case 'langs':
					$query = 'SELECT
							h.id_pages,c.url_pages,c.id_lang,lang.iso_lang
							FROM mc_about_page AS h
							JOIN mc_about_page_content AS c ON(h.id_pages = c.id_pages) 
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
							WHERE h.id_pages = :id AND c.published_pages = 1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']){
				case 'page':
					$query = 'SELECT
								h.*,
								c.name_pages,
								c.longname_pages,
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
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
	}
}