<?php
add_action('wp', 'hw2cf_wp');
add_action('init', 'hw2cf_init');

class Hw2CfPlugin {
    private static $instance = null; 
    
    private $form_controller;
    private $form_view;

    private function __construct() {
        $this->form_controller = new \Hw2CF\FormController();
        $this->form_view = $this->form_controller->getView();
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

    function hw2contracts_head() {
        echo $this->form_view->getHead();
        echo $this->form_view->getScripts();
    }

// mt_settings_page() displays the page content for the Test settings submenu
    function hw2contracts_settings_page() {
        $AdminCtrl = new \Hw2CF\AdminController();
        $AdminCtrl->init();
    }

// action function for above hook
    function hw2contracts_add_pages() {
        // Add a new submenu under Settings:
        add_options_page(__('HW2 Contract Form', 'hw2cf-form'), __('HW2 Contract Form', 'hw2cf-form'), 'manage_options', 'basettings', array($this, 'hw2contracts_settings_page'));
    }

// Hook for adding admin menus

    function hw2contracts_form() {
        $this->form_controller->init();
    }

}

function hw2cf_wp() {
    global $post;

    $hw2cf_plg =  Hw2CfPlugin::I();

    if (isset($post)) {
        $slug = $post->post_name;

        if ($slug == Hw2CF\Opts::I()->hw2cf_page_alias) {
            add_action('wp_head', array($hw2cf_plg, 'hw2contracts_head'));

            add_shortcode('hw2cf_form', array($hw2cf_plg, 'hw2contracts_form'));
        }
    }
}

function hw2cf_init() {
    $hw2cf_plg =  Hw2CfPlugin::I();
    
    if (is_admin()) 
        add_action('admin_menu', array($hw2cf_plg, 'hw2contracts_add_pages'));
}

/**
 *  ADD PROFILE
 * 
 */

function additional_user_fields($user) {
    ?>
    <script type="text/javascript">
        function Hw2CFRedirect() {
            //  open on different tab
            //var win = window.open("<?= bloginfo("url") . '/' . Hw2CF\Opts::I()->hw2cf_page_alias ?>/?user=<?= $user->ID ?>", '_blank');
            //win.focus();
            //  open in same tab
            window.location = "<?= bloginfo("url") . '/' . Hw2CF\Opts::I()->hw2cf_page_alias ?>/<?= $user->user_login ?>";
                }
    </script>
    <button type="button" onclick="Hw2CFRedirect()" id="hw2cf-form-btn" class="float-left submit-button" >Contract Form</button>
    <?php
}

// additional_user_fields
add_action('show_user_profile', 'additional_user_fields');
add_action('edit_user_profile', 'additional_user_fields');


/**
 *  CREATE CUSTOM PERMALINK 
 * 
 */

function hw2cf_flush_rules() {
    $rules = get_option('rewrite_rules');

    if (!isset($rules['('.Hw2CF\Opts::I()->hw2cf_page_alias.')/(.+)$'])) {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}

function hw2cf_insert_rewrite_rules($rules) {
    $newrules = array();
    $newrules['('.Hw2CF\Opts::I()->hw2cf_page_alias.')/(.+)$'] = 'index.php?pagename=$matches[1]&friendly=$matches[2]';

    return $newrules + $rules;
}

function hw2cf_insert_query_vars($vars) {

    array_push($vars, 'friendly');

    return $vars;
}

add_action('wp_loaded', 'hw2cf_flush_rules');
add_filter('rewrite_rules_array', 'hw2cf_insert_rewrite_rules');
add_filter('query_vars', 'hw2cf_insert_query_vars');


