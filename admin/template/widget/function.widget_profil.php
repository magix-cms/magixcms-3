<?php
function smarty_function_widget_profil($params, $template){
    $employeeData = new frontend_db_employee();
    $assign = isset($params['assign']) ? $params['assign'] : 'employeeData';
    $data = $employeeData->fetchData(
        array(
            'type'=>'session'
        ),
        array(
            'keyuniqid_admin'  =>  $_SESSION['keyuniqid_admin']
        )
    );
    $template->assign($assign,$data);
}