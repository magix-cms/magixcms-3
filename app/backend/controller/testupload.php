<?php
class backend_controller_testupload
{
    protected $message, $template, $header, $upload, $pages;

    public $img,$file,$img_multiple;

    /**
     * frontend_controller_testupload constructor.
     */
    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $formClean = new form_inputEscape();
        $this->upload = new component_files_upload();
        $this->pages = new backend_db_pages();

        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
        if(isset($_FILES['img_multiple']["name"])){
            $this->img_multiple = ($_FILES['img_multiple']["name"]);
        }
        if(isset($_FILES['file']["name"])){
            $this->file = http_url::clean($_FILES['file']["name"]);
        }
    }

    /**
     * @param bool $debug
     */
    public function uploadImgFiles($debug = false){
        $this->id = 16;
        $data = $this->pages->fetchData(array('context'=>'unique','type'=>'page'),array('id_pages'=>$this->id));
        //print_r($data);
        $resultUpload = $this->upload->setImageUpload(
            'img',
            array(
                'name'              => filter_rsa::randMicroUI(),
                'edit'              => $data['img_pages'],
                'prefix'            => array('s_'),
                'module_img'        => 'pages',
                'attribute_img'     => 'page',
                'original_remove'   => false
            ),
            array(
                'upload_root_dir'      => 'upload/pages', //string
                'upload_dir'           => $this->id //string ou array
            ),
            false
        );
        print '<pre>';
        print_r($resultUpload);
        print '</pre>';

        //print_r($resultUpload);
        // json response
        //$this->message->json_post_response($resultUpload['statut'], $resultUpload['notify'], $resultUpload['msg']);
    }

    /**
     *
     */
    public function run(){
        if(isset($this->img)){
            $this->uploadImgFiles();
        }/*elseif(isset($this->file)){
            $this->uploadPDFFiles();
        }*/
        if(isset($this->img_multiple)) {
            $this->id = 16;

            $resultUpload = $this->upload->setMultipleImageUpload(
                'img_multiple',
                array(
                    'prefix_name'       => '',
                    'prefix'            => array('s_'),
                    'module_img'        => 'pages',
                    'attribute_img'     => 'page',
                    'original_remove'   => false
                ),
                array(
                    'upload_root_dir' => 'upload/pages', //string
                    'upload_dir' => $this->id //string ou array
                ),
                false
            );
            print '<pre>';
            print_r($resultUpload);
            print '</pre>';
        }
        $this->template->display('test/form/img.tpl');
    }

}
?>