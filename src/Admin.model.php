<?php

namespace Hw2CF;

require_once PATH_HW2CF_PLG . 'src/Admin.controller.php';

class AdminModel {

    public function storeConf($email) {
        update_option("hw2cf_email", $email);
    }

    public function loadData() {
        return Opts::I()->loadFromDb();
    }

    public function deleteData() {
        
    }

}
