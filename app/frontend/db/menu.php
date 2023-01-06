<?php
class frontend_db_menu {
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
		if($config['context'] === 'all') {
			switch ($config['type']) {
			    case 'links':
					$query = "SELECT 
							m.id_link as id_link, 
							m.type_link as type_link, 
							m.mode_link as mode_link, 
							m.id_page, 
							mc.id_lang, 
							l.iso_lang, 
							COALESCE(mc.name_link, pc.name_pages, apc.name_pages, cc.name_cat, pl.name) as name_link,
							mc.title_link as title_link,
							COALESCE(mc.url_link, pc.url_pages, apc.url_pages, cc.url_cat) as url_link,
							COALESCE(pc.published_pages, apc.published_pages, cc.published_cat, 1) as active_link,
							pl.name as plugin_name
						FROM mc_menu as m
						LEFT JOIN mc_menu_content as mc ON m.id_link = mc.id_link
						LEFT JOIN mc_lang as l ON mc.id_lang = l.id_lang
						LEFT JOIN mc_cms_page as p ON m.id_page = p.id_pages AND m.type_link = 'pages'
						LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = l.id_lang
						LEFT JOIN mc_about_page as ap ON m.id_page = ap.id_pages AND m.type_link = 'about_page'
						LEFT JOIN mc_about_page_content as apc ON ap.id_pages = apc.id_pages AND apc.id_lang = l.id_lang
						LEFT JOIN mc_catalog_cat as c ON m.id_page = c.id_cat AND m.type_link = 'category'
						LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = l.id_lang
						LEFT JOIN mc_plugins as pl ON m.id_page = pl.id_plugins
						WHERE l.iso_lang = :iso
						ORDER BY m.order_link";
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
			switch ($config['type']) {
			    case 'plugin':
					$query = 'SELECT id_plugins as id, name FROM mc_plugins WHERE id_plugins = :id';
			    	break;
			    case 'plugin_id':
					$query = 'SELECT id_plugins as id, name FROM mc_plugins WHERE name = :name';
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