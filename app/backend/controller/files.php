<?php
class backend_controller_files extends backend_db_files{
    public $edit, $action, $tabs;
    public $id_config_img, $module_img,$attribute_img,$width_img,$height_img,$type_img,$resize_img;
    protected $message, $template, $header, $data, $fileUpload,$imagesComponent;

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->fileUpload = new component_files_upload();
        $this->imagesComponent = new component_files_images();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }

        // --- ADD or EDIT
        if (http_request::isPost('id')) {
            $this->id_config_img = $formClean->simpleClean($_POST['id']);
        }

        if (http_request::isPost('module_img')) {
            $this->module_img = $formClean->simpleClean($_POST['module_img']);
        }
        if (http_request::isPost('attribute_img')) {
            $this->attribute_img = $formClean->simpleClean($_POST['attribute_img']);
        }
        if (http_request::isPost('width_img')) {
            $this->width_img = $formClean->simpleClean($_POST['width_img']);
        }
        if (http_request::isPost('height_img')) {
            $this->height_img = $formClean->simpleClean($_POST['height_img']);
        }
        if (http_request::isPost('type_img')) {
            $this->type_img = $formClean->simpleClean($_POST['type_img']);
        }
        if (http_request::isPost('resize_img')) {
            $this->resize_img = $formClean->simpleClean($_POST['resize_img']);
        }
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    /**
     * Save Data
     */
    private function save(){
        
        if(isset($this->id_config_img)){
            parent::update(
                array('type'=>'resize'),
                array(
                    'id_config_img'    => $this->id_config_img,
                    'module_img'       => $this->module_img,
                    'attribute_img'    => $this->attribute_img,
                    'width_img'        => $this->width_img,
                    'height_img'       => $this->height_img,
                    'type_img'         => $this->type_img,
                    'resize_img'       => $this->resize_img
                )
            );
            $this->header->set_json_headers();
            $this->message->json_post_response(true,'update',$this->id_config_img);
        }else{

            parent::insert(
                array('type'=>'newResize'),
                array(
                    'module_img'       => $this->module_img,
                    'attribute_img'    => $this->attribute_img,
                    'width_img'        => $this->width_img,
                    'height_img'       => $this->height_img,
                    'type_img'         => $this->type_img,
                    'resize_img'       => $this->resize_img
                )
            );
            $this->header->set_json_headers();
            $this->message->json_post_response(true,'add_redirect');

        }
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delResize':
                parent::delete(
                    array(
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }


    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->module_img) && isset($this->attribute_img)){
                        $this->save();
                    }else{
                        $module = $this->imagesComponent->module();
                        $this->template->assign('module',$module);
                        $type = $this->imagesComponent->type();
                        $this->template->assign('type',$type);
                        $resize = $this->imagesComponent->resize();
                        $this->template->assign('resize',$resize);
                        $this->template->display('files/add.tpl');
                    }
                    break;

                case 'edit':
                    if(isset($this->id_config_img)){
                        $this->save();
                    }else{
                        $this->getItems('size',$this->edit);
                        $module = $this->imagesComponent->module();
                        $this->template->assign('module',$module);
                        $type = $this->imagesComponent->type();
                        $this->template->assign('type',$type);
                        $resize = $this->imagesComponent->resize();
                        $this->template->assign('resize',$resize);
                        $this->template->display('files/edit.tpl');
                    }
                    break;

                case 'delete':
                    if(isset($this->id_config_img)) {
                        $this->del(
                            array(
                                'type'=>'delResize',
                                'data'=>array(
                                    'id' => $this->id_config_img
                                )
                            )
                        );
                    }
                    break;
            }
        }else{
            $this->getItems('sizes');
            $this->template->display('files/index.tpl');
        }
    }
}
?>