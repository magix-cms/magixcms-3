<?php
class frontend_model_plugins{
    /**
     * @param $className
     * @return mixed
     */
    public function getCallClass($className){
        try{
            $class =  new $className;
            if($class instanceof $className){
                return $class;
            }else{
                throw new Exception('not instantiate the class: '.$className);
            }
        } catch (Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
}
?>