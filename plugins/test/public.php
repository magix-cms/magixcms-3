<?php

/**
 * Class plugins_test_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_test_public{
    protected $template;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new frontend_model_template();
    }
    public function override($config,$custom){
        if($config['controller']['name'] === 'pages') {
            if ($config['data'] === 'parent') {
                $conditions = '';
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent IS NULL ';

                if (isset($custom['select'])) {

                    $conditions .= ' AND p.id_pages IN (' . $config['id'] . ') ';
                }
                if (isset($custom['exclude'])) {

                    $conditions .= ' AND p.id_pages NOT IN (' . $config['id'] . ') ';
                }

                if ($config['type'] == 'menu') {
                    $conditions .= ' AND p.menu_pages = 1';
                }
                // ORDER
                $conditions .= ' ORDER BY p.order_pages ASC';

                if ($config['limit'] != null) {
                    $conditions .= ' LIMIT ' . $config['limit'];
                }

                if ($conditions != '') {
                    $data = $this->fetchData(
                        array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
                        array(
                            ':iso' => $config['lang']
                        )
                    );
                }


            }elseif ($config['data'] === 'child') {
                if($custom['context'] === 'all') {
                    $conditions = '';
                    $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1
                    AND p.id_parent = :id';

                    if (isset($custom['select'])) {

                        $conditions .= ' AND p.id_pages IN (' . $config['id'] . ') ';
                    }
                    if (isset($custom['exclude'])) {

                        $conditions .= ' AND p.id_pages NOT IN (' . $config['id'] . ') ';
                    }

                    if ($config['type'] == 'menu') {
                        $conditions .= ' AND p.menu_pages = 1';
                    }

                    $conditions .= ' GROUP BY p.id_pages ORDER BY p.order_pages ASC';

                    if ($config['limit'] != null) {
                        $conditions .= ' LIMIT ' . $config['limit'];
                    }

                    if ($conditions != '') {
                        $data = $this->fetchData(
                            array('context' => 'all', 'type' => 'child', 'conditions' => $conditions),
                            array(
                                ':iso' => $config['lang'],
                                ':id' => $config['id_pages']
                            )
                        );
                    }

                }elseif($custom['context'] === 'child') {
                    $conditions = '';
                    $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent = :id';

                    if ($config['type'] == 'menu') {
                        $conditions .= ' AND p.menu_pages = 1';
                    }

                    $conditions .= ' GROUP BY p.id_pages';
                    if ($config['sort'] != null) {
                        $conditions .= ' ORDER BY p.order_pages';
                    }
                    if ($config['limit'] != null) {
                        $conditions .= ' LIMIT ' . $config['limit'];
                    }

                    $data = $this->fetchData(
                        array(
                            'context' => 'all',
                            'type' => 'child',
                            'conditions' => $conditions
                        ),
                        array(
                            ':iso' => $config['lang'],
                            ':id' => $config['id'])
                    );
                }
            }
        }
        return $data;
    }
    public function run(){
        $this->template->display('test/index.tpl');
    }
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'pages') {

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT
                    p.*,c.*,lang.iso_lang
                    FROM mc_cms_page AS p
                    JOIN mc_cms_page_content AS c ON(p.id_pages = c.id_pages) 
                    JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang) 
                    $conditions";

                    $params = $data;

                    //WHERE lang.iso_lang = :iso AND c.published_pages = 1
                }elseif($config['type'] === 'child'){

                    $config["conditions"] ? $conditions = $config["conditions"] : $conditions = '';

                    $sql = "SELECT p.id_pages,p.id_parent,p.img_pages,p.menu_pages, p.date_register, c.*,lang.iso_lang
                    FROM mc_cms_page AS p
                        JOIN mc_cms_page_content AS c USING ( id_pages )
                        JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
                        LEFT JOIN mc_cms_page AS pa ON ( p.id_parent = pa.id_pages )
                        LEFT JOIN mc_cms_page_content AS ca ON ( pa.id_pages = ca.id_pages ) 
                        $conditions";

                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }elseif($config['context'] === 'one') {

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}
?>