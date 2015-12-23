<?php

namespace Hw2CF;

class Field {

    public $alias;
    public $title;
    public $isRequired;
    public $type;
    public $ftype;
    public $attr;
    public $customTmpl;

    /**
     * 
     * @param string $ftype
     * @param string $alias
     * @param string $title
     * @param string $type
     * @param array $options
     * string attr: set custom attributes for field
     * boolean isRequired: set field as required
     */
    public function __construct($ftype,$alias, $title, $type = "text", $options = array()) {
        $this->ftype = $ftype;
        $this->alias = $alias;
        $this->title = $title;
        $this->type = $type;
        if (is_array($options) && count($options) > 0) {
            foreach ($options as $key => $val)
                $this->$key = $val;
        }
    }

    /**
     * 
     * @param \Hw2CF\FormView $formView
     * @return string
     */
    public function getAttr($formView) {
        $attr=isset($this->attr ) ? $this->attr : "";
            
        if ($this->ftype == "prop" && !$formView->isAdmin()) {
            $attr.=" readonly";
        } else if ($this->ftype == "user") {
            if ($formView->hasUserCompiled() && !$formView->isAdmin()) {
                $attr.=" readonly";
            }
        }
        
        return $attr;
    }

    public function getFullAlias() {
        return "hw2cf_" . $this->ftype . "_" . $this->alias;
    }

}
