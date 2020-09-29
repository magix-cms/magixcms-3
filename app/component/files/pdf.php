<?php
/**
 * class for generate pdf file
 */
use Dompdf\Options;
use Dompdf\Canvas;
use Dompdf\Dompdf;
use Dompdf\Helpers;
use Dompdf\Exception;
use Dompdf\Image;
use Dompdf\Image\Cache;

class component_files_pdf{
    protected $dompdf,$message, $template, $header,$filesystem;
    protected $default = array(
        'template'  =>  '',
        'lang'  	=>  '',
        'stream'    =>  false,
        'pathfile'  =>  '',
        'filename'  =>  '',
        'paper'     =>  'portrait'
    );
    public $preview,$filenumber,$documentype;
    /**
     * component_files_pdf constructor.
     */
    function __construct($t = null,$debug = false)
    {
        $this->header = new http_header();
		$this->template = $t;
        $this->message = new component_core_message($this->template);
        $formClean = new form_inputEscape();
        $this->filesystem = new filesystem_makefile();
        $options = new Options();
        //$options->set('tempDir', __DIR__ . '/site_uploads/dompdf_temp');
        $options->set('isRemoteEnabled', true);
        //$options->set('defaultFont', 'Courier');
        if($debug) {
            $options->set('debugKeepTemp', true);
            $options->set('debugPng', true);
        }
        //$options->set('chroot', '/'); // Just for testing :)
        $options->set('isHtml5ParserEnabled', true);
        $this->dompdf = new Dompdf($options);
        /**
         *
         */
        if (http_request::isGet('preview')) {
            $this->preview = $formClean->simpleClean($_GET['preview']);
        }
        if (http_request::isGet('filenumber')) {
            $this->filenumber = $formClean->simpleClean($_GET['filenumber']);
        }
        if (http_request::isGet('documentype')) {
            $this->documentype = $formClean->simpleClean($_GET['documentype']);
        }
    }

    /**
     *
     * $filesPdf = new component_files_pdf()
     * $filesPdf->create(array(
        'template'  =>  'pdf/index.tpl',
        'stream'    =>  false,
        'pathfile'  =>  '/upload/pdf/3/',
        'filename'  =>  'file-number-0001.pdf',
        'paper'     =>  'portrait'
     ));
     * @param array $config
     */
    public function create($config = array()){
        $config = $config + $this->default;
        /*
        $canvas = $this->dompdf->getCanvas();
        $canvas->image(http_url::getUrl().'/skin/img/logo/test.png',0, 0, 125, 125);
        */

        //print_r($config);
        if(is_array($config)){
        	//if($config['lang'] != '') $this->template->configLangLoad($config['lang']);
            $config['lang'] = $this->template->lang;
            $fetch = $this->template->fetch($config['template']);
            $pdf = $this->dompdf;
            $pdf->output(['isRemoteEnabled' => true]);
            // instantiate and use the dompdf class
            $pdf->loadHtml($fetch);
            // (Optional) Setup the paper size and orientation
            $pdf->setPaper('A4', $config['paper']);//landscape
            /*$file_name = md5(uniqid(mt_rand(), true));
            $pdf_file_name = $file_name . '.pdf';*/
            // Render the HTML as PDF
			$pdf->render();
            // Create files
            if(file_exists(component_core_system::basePath().$config['pathfile'])){
                file_put_contents(component_core_system::basePath().$config['pathfile'].$config['filename'], $this->dompdf->output());
            }else{
                //Create dir if not exist
                $this->filesystem->mkdir(component_core_system::basePath().$config['pathfile']);
                file_put_contents(component_core_system::basePath().$config['pathfile'].$config['filename'], $this->dompdf->output());
            }

            // Output the generated PDF to Browser
            if($config['stream']){
                $pdf->stream($config['filename'],array(
                    'compress'      =>  1,
                    'Attachment'    =>  0
                ));
            }
        }
    }
    /*public function test(){
        $this->create(array(
            'template'  =>  'pdf/index.tpl',
            'stream'    =>  false,
            'pathfile'  =>  '/upload/pdf/2/',
            'filename'  =>  'file-number-0001.pdf'
        ));
    }*/

    /**
     * Execute
     */
    /*public function run(){
        if(isset($this->filenumber)){
            if(isset($this->documentype)){
                if(isset($this->preview)){
                    $this->template->display('pdf/index.tpl');
                }else{
                    $this->test();
                }
            }
        }
    }*/
}
?>