<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * @category   DB CLass 
 * @package    Magix CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien, 
 * http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.2.0
 * @author Gérits Aurélien <aurelien@magix-cms.com> <aurelien@magix-dev.be>
 *
 */
class backend_db_setting{
    /**
     * @param $config
     * @param bool $data
     * @return mixed
     * @throws Exception
     */
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'settings') {
                    $sql = 'SELECT * FROM mc_setting';
                }elseif ($config['type'] === 'cssinliner') {
                    $sql = 'SELECT * FROM mc_cssinliner';
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {

                if ($config['type'] === 'role') {

                    //Return role list
                    $sql = 'SELECT * FROM mc_setting
                    WHERE id_setting = :id';
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
    public function update($config,$data = false)
    {
        if (is_array($config)) {
            if ($config['type'] === 'general') {
                $queries = array(
                    array(
                        'request'=>"UPDATE mc_setting SET value = :content_css WHERE name = 'content_css'",
                        'params'=>array(':content_css' => $data['content_css'])
                    ),
                    array(
                        'request'=>"UPDATE mc_setting SET value = :concat WHERE name = 'concat'",
                        'params'=>array(':concat' => $data['concat'])
                    ),
                    array(
                        'request'=>"UPDATE mc_setting SET value = :ssl WHERE name = 'ssl'",
                        'params'=>array(':ssl' => $data['ssl'])
                    ),
                    array(
                        'request'=>"UPDATE mc_setting SET value = :cache WHERE name = 'cache'",
                        'params'=>array(':cache' => $data['cache'])
                    ),
                    array(
                        'request'=>"UPDATE mc_setting SET value = :mode WHERE name = 'mode'",
                        'params'=>array(':mode' => $data['mode'])
                    )
                );
                component_routing_db::layer()->transaction($queries);
            }elseif ($config['type'] === 'css_inliner') {
                if($data['css_inliner'] != '0'){
                    $queries = array(
                        array(
                            'request'=>"UPDATE mc_setting SET value = :css_inliner WHERE name = 'css_inliner'",
                            'params'=>array(':css_inliner' => $data['css_inliner'])
                        ),
                        array(
                            'request'=>"UPDATE mc_css_inliner SET color_cssi = :header_bg WHERE property_cssi = 'header_bg'",
                            'params'=>array(':header_bg' => $data['header_bg'])
                        ),
                        array(
                            'request'=>"UPDATE mc_css_inliner SET color_cssi = :header_c WHERE property_cssi = 'header_c'",
                            'params'=>array(':header_c' => $data['header_c'])
                        ),
                        array(
                            'request'=>"UPDATE mc_css_inliner SET color_cssi = :footer_bg WHERE property_cssi = 'footer_bg'",
                            'params'=>array(':footer_bg' => $data['footer_bg'])
                        ),
                        array(
                            'request'=>"UPDATE mc_css_inliner SET color_cssi = :footer_c WHERE property_cssi = 'footer_c'",
                            'params'=>array(':footer_c' => $data['footer_c'])
                        )
                    );
                    component_routing_db::layer()->transaction($queries);
                }else{
                    $sql = "UPDATE mc_setting SET value = :css_inliner WHERE name = 'css_inliner'";
                    component_routing_db::layer()->update($sql,
                        array(
                            ':css_inliner'	    => $data['css_inliner']
                        )
                    );
                }
            }elseif ($config['type'] === 'google') {
                $queries = array(
                    array(
                        'request'=>"UPDATE mc_setting SET value = :analytics WHERE name = 'analytics'",
                        'params'=>array(':analytics' => $data['analytics'])
                    ),
                    array(
                        'request'=>"UPDATE mc_setting SET value = :robots WHERE name = 'robots'",
                        'params'=>array(':robots' => $data['robots'])
                    )
                );
                component_routing_db::layer()->transaction($queries);
            }
        }
    }

	/**
	 * singleton dbnews
	 * @access public
	 * @var void
	 */
	/*static public $publicdbsetting;
	/**
	 * instance backend_db_home with singleton
	 */
	/*public static function publicDbSetting(){
        if (!isset(self::$publicdbsetting)){
         	self::$publicdbsetting = new backend_db_setting();
        }
    	return self::$publicdbsetting;
    }

	/**
	 * Retourne le setting selectionner
	 * @param $setting_id (string) identifiant du setting
	 * @return mixed
	 */
    /*public function s_uniq_setting_value($setting_id){
    	$sql = 'SELECT setting_value FROM ap_setting WHERE setting_id = :setting_id';
		return component_routing_db::layer()->fetch($sql,array(':setting_id'	=>	$setting_id));
    }

    /**
	 * Return all settings
	 * @return mixed
	 */
    /*public function s_all_settings(){
    	$sql = 'SELECT setting_id,setting_value FROM ap_setting';
		return component_routing_db::layer()->fetchAll($sql);
    }

    /**
     * @return array
     */
    /*public function fetchCSSIColor(){
        $sql = 'SELECT color.*
    	FROM ap_css_inliner_color as color';
        return component_routing_db::layer()->fetchAll($sql);
    }*/
}