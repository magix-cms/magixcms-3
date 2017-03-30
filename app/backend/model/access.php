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
 * @author Gérits Aurélien <aurelien@magix-cms.com> <aurelien@magix-dev.be>
 * @copyright  MAGIX CMS Copyright (c) 2010 -2014 Gerits Aurelien,
 * @version  Release: 1.0
 * Date: 07/03/14
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 */
class backend_model_access extends backend_db_employee{
    /**
     * @return array
     */
    public static function default_access(){
        return self::$default_access;
    }
    /*
     * Retourne un tableau des données de sessions
     */
    public function dataSession(){
        $data_session = parent::fetchData(
            array(
                'type'=>'session'
            ),
            array(
                'keyuniqid_admin'  =>  $_SESSION['keyuniqid_admin']
            )
        );
        return $data_session;
    }

    /**
     * Return data employee
     * @param $data_session
     * @param $class_name
     * @return array
     */
    public function dataEmployee($data_session,$class_name){
        $id_admin = $data_session['id_admin'];
        $id_role = $data_session['id_role'];
        $data_access = parent::fetchData(
            array(
                'type'=>'currentAccess'
            ),
            array(
                'id_role'  =>  $id_role,
                'class_name'  =>  $class_name
            )
        );
        $access['view']   = $data_access['view_access'];
        $access['add']    = $data_access['add_access'];
        $access['edit']   = $data_access['edit_access'];
        $access['delete'] = $data_access['delete_access'];
        $access['action'] = $data_access['action_access'];
        return $access;
    }

    /**
     * Return all role access
     * @param $data_session
     * @return array
     */
    public function allDataEmployee($data_session){
        $id_role = $data_session['id_role'];
        $array_access = parent::fetchData(
            array(
                'type'=>'access'
            ),
            array(
                'id_role'  =>  $id_role
            )
        );
        foreach($array_access as $key){
            $class_name[$key['class_name']]= array(
                'view_access'   =>  $key['view_access'],
                'add_access'    =>  $key['add_access'],
                'edit_access'   =>  $key['edit_access'],
                'delete_access' =>  $key['delete_access'],
                'action_access' =>  $key['action_access']
            );
        }
        return $class_name;
    }

    /**
     * @param $class_name
     * @return array
     */
    public function module_access($class_name){
        $all_access = self::allDataEmployee(self::dataSession());
        $access['view']   = $all_access[$class_name]['view_access'];
        $access['add']    = $all_access[$class_name]['add_access'];
        $access['edit']   = $all_access[$class_name]['edit_access'];
        $access['delete'] = $all_access[$class_name]['delete_access'];
        $access['action'] = $all_access[$class_name]['action_access'];
        return $access;
    }
}
?>