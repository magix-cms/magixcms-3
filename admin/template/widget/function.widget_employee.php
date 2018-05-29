<?php
/**
 * @param $params
 * @param $template
 */
function smarty_function_widget_employee($params, $template){
    $employeeData = new backend_db_employee();
    $assign = isset($params['assign']) ? $params['assign'] : 'employeeData';
    $data = $employeeData->fetchData(
        array('context' => 'one', 'type' => 'session'),
        array('keyuniqid_admin'  =>  $_SESSION['keyuniqid_admin'])
    );
    $template->assign($assign,$data);
}