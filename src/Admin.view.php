<?php

namespace Hw2CF;

use Hw2CF;

require_once PATH_HW2CF_PLG . 'src/Admin.controller.php';

class AdminView {

    private $controller;
    private $model;

    /**
     * 
     * @param \Hw2CF\FormController $controller
     */
    public function __construct($controller) {
        $this->controller = $controller;
        $this->model = $controller->getModel();
    }

    public function getRender() {
        ob_start();

        // Now display the settings editing screen

        echo '<div class="wrap">';

        // header

        echo "<h2>" . __('Hw2 Contract Form Settings', Opts::I()->hw2cf_page_alias) . "</h2>";

        // settings form
        ?>

        <form name="form-hw2cf-settings" method="post" action="">
            <p>Email for contract form: 
                <input type="email" name="hw2cf_email" value="<?= Opts::I()->hw2cf_email; ?>" size="20" required>
            </p><hr />

            <p class="submit">
                <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            </p>

        </form>
        </div>

        <?php
        return ob_get_clean();
    }

}
