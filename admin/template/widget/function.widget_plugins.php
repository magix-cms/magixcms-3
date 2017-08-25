<?php
/**
 * @param $params
 * @param $template
 */
function smarty_function_widget_plugins($params, $template){
    $pluginsData = new backend_model_plugins();
    $assign = isset($params['assign']) ? $params['assign'] : 'getItemsPlugins';
    $data = $pluginsData->getItems(array('type'=>'self'));
    $template->assign($assign,$data);
}