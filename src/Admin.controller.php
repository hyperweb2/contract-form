<?php

namespace Hw2CF;

require_once PATH_HW2CF_PLG . 'src/Admin.view.php';
require_once PATH_HW2CF_PLG . 'src/Admin.model.php';

class AdminController {
    /**
     *
     * @var AdminView
     */
    private $view;

    /**
     *
     * @var AdminModel 
     */
    private $model;
    
    private $data;

    public function __construct() {
        $this->model = new AdminModel();
        $this->view = new AdminView($this);
        $this->data = $this->model->loadData();
    }

    public function init() {
        //must check that the user has the required capability 
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // See if the user has posted us some information
        // If they did, this hidden field will be set to 'Y'

        if (isset($_POST["hw2cf_email"])) {
            $this->model->storeConf($_POST["hw2cf_email"]);

            $this->data = $this->model->loadData(); // reload confs
            
            // Put a "settings saved" message on the screen
            ?>
            <div class="updated"><p><strong>Option saved</strong></p></div>
            <?php
            
        }

        echo $this->getView()->getRender();
    }

    /**
     * 
     * @return AdminView
     */
    public function getView() {
        return $this->view;
    }

    /**
     * 
     * @return AdminModel
     */
    public function getModel() {
        return $this->model;
    }

}
