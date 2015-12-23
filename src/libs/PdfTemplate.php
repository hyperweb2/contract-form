<?php

namespace Hw2CF;

class PdfTemplate {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getTemplate() {
        $imgData = $this->data["hw2cf_user_signature"][0];
        FormHelper::signToImage($imgData, PATH_HW2CF_PLG . "user-sign.png");

        $imgData = $this->data["hw2cf_prop_signature"][0];
        FormHelper::signToImage($imgData, PATH_HW2CF_PLG . "prop-sign.png");
        
        ob_start();
        
        $tmpl=FormHelper::findTemplateFile("hw2cf/pdf/contract.php");
        if (!$tmpl)
            $tmpl=PATH_HW2CF_PLG.'src/templates/pdf/contract.php';
        
        include $tmpl;

        return ob_get_clean();
    }
    
    public function getData($type,$alias) {
        return isset($this->data["hw2cf_".$type."_".$alias]) ? $this->data["hw2cf_".$type."_".$alias][0] : "";
    }
}
