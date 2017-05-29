<?php
class frontend_db_home
{
    /**
     * Load data home by lang
     * @access protected
     * @param array $data
     * @return array
     */
    protected function fetch($data)
    {
        if(is_array($data)) {
            $sql = 'SELECT
                h.*,c.*,lang.iso_lang
				FROM mc_home_page AS h
				JOIN mc_home_page_content AS c ON(h.id_page = c.id_page) 
				JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
				WHERE lang.iso_lang = :iso AND c.published = 1';
            return component_routing_db::layer()->fetch($sql, array(
                ':iso' => $data['iso']
            ));
        }
    }
}
?>