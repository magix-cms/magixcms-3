<?php
class component_files_images{

    protected $configCollection;

    /**
     * component_files_images constructor.
     */
    public function __construct()
    {
        $this->configCollection = new component_collections_config();
    }

    /**
     * @return array
     */
    public function prefix(){
        return array('small'=>'s_','medium'=>'m_','large'=>'l_');
    }

    /**
     * @param $data
     * @return mixed|null
     */
    public function getConfigItems($data){
        return $this->configCollection->fetchData(
            array(
                'context'=>'all',
                'type'=>'imgSize'
            ),
            array(
                'module_img'    =>$data['module_img'],
                'attribute_img' =>$data['attribute_img']
            )
        );
    }
}
?>