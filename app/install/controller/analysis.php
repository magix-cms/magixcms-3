<?php
class install_controller_analysis{

    public function getBuildItems(){
        if (version_compare(phpversion(),'5.4','<')) {
            $data['php'] = 0;
        }else{
            $data['php'] = 1;
        }
        if (!function_exists('mb_detect_encoding')) {
            $data['encoding'] = 0;
        }else{
            $data['encoding'] = 1;
        }
        if (!function_exists('iconv')) {
            $data['iconv'] = 0;
        }else{
            $data['iconv'] = 1;
        }
        if (!function_exists('ob_start')) {
            $data['ob'] = 0;
        }else{
            $data['ob'] = 1;
        }
        if (!function_exists('simplexml_load_string')) {
            $data['xml'] = 0;
        }else{
            $data['xml'] = 1;
        }
        if (!function_exists('dom_import_simplexml')) {
            $data['dom'] = 0;
        }else{
            $data['dom'] = 1;
        }
        if (!function_exists('spl_classes')) {
            $data['spl'] = 0;
        }else{
            $data['spl'] = 1;
        }
        if(!is_writable(component_core_system::basePath().'app'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR)){
            $data['writable_config'] = 0;
        }else{
            $data['writable_config'] = 1;
        }
        if(!is_writable(component_core_system::basePath().'var'.DIRECTORY_SEPARATOR)){
            $data['writable_var'] = 0;
        }else{
            $data['writable_var'] = 1;
        }

        return $data;
    }

    /**
     *
     */
    public function run(){
        install_model_smarty::getInstance()->assign('getBuildItems',$this->getBuildItems());
        install_model_smarty::getInstance()->display('analysis/index.tpl');
    }
}
?>