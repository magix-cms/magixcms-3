<?php
class frontend_db_menu
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
				if ($config['type'] === 'links') {
					$sql = "SELECT 
								m.id_link as id_link, 
								m.type_link as type_link, 
								m.mode_link as mode_link, 
								m.id_page, 
								mc.id_lang, 
								l.iso_lang, 
								mc.name_link as name_link, 
								mc.title_link as title_link,
								COALESCE(mc.url_link, pc.url_pages, apc.url_pages, cc.url_cat) as url_link,
								COALESCE(pc.published_pages, apc.published_pages, cc.published_cat, 1) as active_link
							FROM mc_menu as m
							LEFT JOIN mc_menu_content as mc ON m.id_link = mc.id_link
							LEFT JOIN mc_lang as l ON mc.id_lang = l.id_lang
							LEFT JOIN mc_cms_page as p ON m.id_page = p.id_pages AND m.type_link = 'pages'
							LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = l.id_lang
							LEFT JOIN mc_about_page as ap ON m.id_page = ap.id_pages AND m.type_link = 'about_page'
							LEFT JOIN mc_about_page_content as apc ON ap.id_pages = apc.id_pages AND apc.id_lang = l.id_lang
							LEFT JOIN mc_catalog_cat as c ON m.id_page = c.id_cat AND m.type_link = 'category'
							LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = l.id_lang
							WHERE l.iso_lang = :iso
							ORDER BY m.order_link ASC";
					$params = $data;
				}

				return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
			}
			/*elseif($config['context'] === 'one') {

				return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
			}*/
		}
	}
}
?>