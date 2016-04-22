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
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 13/12/13
 * Time: 18:31
 * License: Dual licensed under the MIT or GPL Version
 */
class component_format_table{

    /**
     * @param $nid
     * @param $class
     * @param $id
     */
    private function getClassAndId($nid,&$class,&$id)
    {
        if (is_array($nid)) {
            $class = ' class="'.$nid[0].'"';
            $id = !empty($nid[1]) ? ' id="'.$nid[1].'"' : null;
        } else {
            $class = !empty($nid) ? ' class="'.$nid.'"': null;
            $id = null;
        }
    }

    /**
     * @param $sclass
     * @param $class
     * @param $style
     */
    private function getStyleAndClass($sclass,&$class,&$style)
    {
        if (is_array($sclass)) {
            $class = ' class="'.$sclass[0].'"';
            $style = !empty($sclass[1]) ? ' style="'.$sclass[1].'"' : null;
        } else {
            $class = !empty($sclass) ? ' class="'.$sclass.'"': null;
            $style = null;
        }
    }


    /**
     * @param $trconfig
     * @param $trclass
     * @param $trstyle
     */
    private function line($trconfig,&$trclass,&$trstyle){
        return self::getStyleAndClass($trconfig,$trclass,$trstyle);
    }

    /**
     * @param $tdconfig
     * @param $tdclass
     * @param $tdstyle
     */
    private function col($tdconfig,&$tdclass,&$tdstyle){
        return self::getStyleAndClass($tdconfig,$tdclass,$tdstyle);
    }

    /**
     * @static
     * @param $data
     * @param array $params
     * @return array
     * @throws Exception
     * @example:
     * component_html_format::parseArray(
    $db->myrequest(),array('params_key','params_val')
    );
     */
    public static function parseArray($data,array $params){
        if(is_array($params)){
            $array_key = '';
            $array_val = '';
            foreach($data as $key){
                $array_key[]= $key[$params[0]];
                $array_val[]= $key[$params[1]];
            }
            return array_combine($array_key,$array_val);
        }else{
            throw new Exception(sprintf('$params is not array : %s', $params));
        }
    }
    /**
     * @static
     * @param $tablenid
     * @param $trconfig
     * @param $tdconfig
     * @param array $valPostName
     * @param array $data
     * @internal param $nid
     * @return string
     * @example :
     *
     * Données statique venant par exemple d'un formulaire
     *
    $tablenid = array('classtable','monid');
    $trconfig = null;
    $tdconfig = array('maclass');
    $valPostName = array(
    'nom'=>'<label>Nom : </label>',
    'test'=>'<label>Test : </label>'
    );
    $data = array(
    'nom'=>'<p>aurelien</p>',
    'test'=>'<p>autre test</p>'
    );
    $table_test = component_format_table::get(
    $tablenid,
    $trconfig,
    $tdconfig,
    $valPostName,
    $data
    );
     *
     *  Configure dynamiquement les données à convertir en tableau HTML venant de la base de données
     *
    $tablenid = array('classtable','monid');
    $trconfig = null;
    $tdconfig = array('maclass');
    $valPostName = array(
    'nom'=>'<label>Nom : </label>',
    'test'=>'<label>Test : </label>'
    );
    $data = component_format_table::parseArray(
    $dbhome->s_lang_home(),
    array('name_home','desc_home')
    );
    $table_test = component_format_table::get(
    $tablenid,
    $trconfig,
    $tdconfig,
    $valPostName,
    $data
    );
     *
     */
    public static function get($tablenid, $trconfig, $tdconfig, $valPostName,array $data){
        //ID, class du tableau
        self::getClassAndId($tablenid,$tableclass,$tableid);
        //Les lignes du tableau
        self::line($trconfig,$trclass,$trstyle);
        //Les colonnes du tableau
        self::col($tdconfig,$tdclass,$tdstyle);
        $row_line = '';
        foreach($data as $key => $val){
            //Vérifie si une transposition en langage humain existe
            if(is_array($valPostName)){
                if(array_key_exists($key,$valPostName)){
                    $key_clean = $valPostName[$key];
                }else{
                    $key_clean = $key;
                }
            }else{
                $key_clean = $key;
            }
            //Condition pour exclure certaines donnée (ex: type de formulaire)
            if($key != 'form_type'){
                // Si la valeur post est un tableaux on converti en chaîne de caractère
                if(is_array($val)){
                    $val = implode(', ',$val);
                }
                $row_line .= '<tr'.$trclass.$trstyle.'>'."\n";
                $row_line .= '<td'.$tdclass.$tdstyle.'>'."\n";
                $row_line .= $key_clean."\n";
                $row_line .='</td><td'.$tdclass.$tdstyle.'>'."\n";
                $row_line .= $val."\n";
                $row_line .= '</td></tr>'."\n";
            }
        }
        //Construction du tableau et retour
        $table = '<table'.$tableid.$tableclass.'>'."\n";
        $table .= '<tbody>'."\n";
        $table .= $row_line;
        $table .= '</tbody>'."\n";
        $table .= '</table>';
        return $table;
    }
}