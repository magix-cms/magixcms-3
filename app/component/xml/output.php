<?php
class component_xml_output extends XMLWriter{
    /**
     * @var
     */
    protected $xmlwriter;

    /**
     * magixglobal_model_outputxml constructor.
     */
    function __construct(){
        $this->openMemory();
        $this->setIndent(true);
        $this->startDocument('1.0', 'UTF-8');
        $this->startElement('magixcms');
    }

    /**
     *
     */
    public function getXmlHeader(){
        header('Content-type: text/xml; charset=UTF-8');
    }
    /**
     * @param $data
     */
    public function setElement($data){
        $this->startElement($data['start']);
        if(array_key_exists('attr', $data)){
            // if attr is array
            if(is_array($data['attr'])){
                foreach($data['attr'] as $key => $value) {
                    if(is_array($value)){
                        $this->startAttribute($value['name']);
                        $this->text($value['content']);
                        $this->endAttribute();
                    }
                }
            }
        }
        if(array_key_exists('attrNS', $data)){
            // if attrNS is array
            if(is_array($data['attrNS'])){
                foreach($data['attrNS'] as $key => $value) {
                    if(is_array($value)) {
                        $this->startAttributeNS($value['prefix'], $value['name'], $value['uri']);
                        $this->text($value['uri']);
                        $this->endAttribute();
                    }
                }
            }
        }
        if(array_key_exists('cData', $data)){
            $this->writeCData($data['cData']);
        }
        if(array_key_exists('text',$data)){
            $this->text($data['text']);
        }
        $this->endElement();
    }

    /**
     * @param $name
     */
    public function newStartElement($name){
        $this->startElement($name);
    }

    /**
     * End root Element
     */
    public function newEndElement(){
        $this->endElement();
    }

    /**
     * Output document
     */
    public function output(){
        $this->endElement();
        $this->endDocument();
        print $this->outputMemory(TRUE);
    }
}