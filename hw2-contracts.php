<?php
/*
  Plugin Name: HW2 Contract Form
  Description: Provides an agreement form
  Version: 1.0
  Author: Giuseppe Ronca
 */



//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


define("PATH_HW2CF_PLG", plugin_dir_path(__FILE__));
define("URL_HW2CF_PLG", plugin_dir_url(__FILE__));

require_once PATH_HW2CF_PLG . 'src/libs/FormHelper.php';
require_once PATH_HW2CF_PLG . 'src/libs/PdfTemplate.php';
require_once PATH_HW2CF_PLG . 'src/libs/SysEvents.php';
require_once PATH_HW2CF_PLG . 'src/libs/Options.php';
require_once PATH_HW2CF_PLG . 'src/entities/Field.php';
require_once PATH_HW2CF_PLG . 'src/Form.controller.php';
require_once PATH_HW2CF_PLG . 'src/Admin.controller.php';
require_once PATH_HW2CF_PLG . 'config/conf.php';
require_once PATH_HW2CF_PLG . 'src/wp-functions/wp.php';

