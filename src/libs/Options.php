<?php

namespace Hw2CF;

class Opts {
    
    private static $instance=null;
    
    public $hw2cf_name="Hw2 Contracts";
    public $hw2cf_company_name="Hw2";
    public $hw2cf_company_alias="hw2";
    public $hw2cf_email="";
    public $hw2cf_page_alias="hw2cf-form";
    public $hw2cf_fields = array(
        "user"=>array(),
        "prop"=>array()
    );
    
    public function loadFromArray($confs) {
        foreach ($confs as $conf => $value) {
            $this->$conf=$value; // variables variable ( created dynamically if not exists )
        }
    }
    
    public function loadFromDb() {
        $confs=get_object_vars($this);
        foreach ($confs as $conf => $value) {
            $this->$conf=get_option($conf, $value); // variables variable ( created dynamically if not exists )
        }
    }
    
    private function __construct() {
        $this->loadFromDb();
    }
    
    /**
     * Singleton
     * @return Opts
     */
    public static function I() {
        if (!self::$instance) {
            self::$instance=new self();
        }
        
        return self::$instance;
    }
    
    public function getConfs() {
        return get_object_vars();
    }
    
    public function addField($ftype,$alias,$title,$type="text",$options=array()) {
        $this->hw2cf_fields[$ftype][$alias]=new Field($ftype, $alias, $title,$type,$options);
    }
    
    /**
     * 
     * @param string $alias
     * @return \Hw2CF\Field
     */
    public function getField($ftype,$alias) {
        return $this->hw2cf_fields[$ftype][$alias];
    }

}