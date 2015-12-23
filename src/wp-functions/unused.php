<?php

function hw2contracts_user_login($user_login, $user) {

    $user_id = $user->ID;

    $role = \Hw2CF\FormHelper::getUserFirstRole($user->ID);

    if ($role == "hw2_contributor" || $role == "hw2_partner") {
        $first_login = get_user_meta($user_id, 'first_login_done', false);
        if ($first_login == false) {
            // update meta after first login
            update_user_meta($user_id, 'first_login_done', '1');
            // redirect to given URL
            wp_redirect(bloginfo("url").'/'.Conf::$hw2cf_page_alias."/?user=" . $user->ID);
            exit;
        }
    }
}

// hook when user logs in
add_action('wp_login', 'hw2contracts_user_login', 10, 2);



function hw2contracts_title($data) {
    if (get_post_field('post_name', get_post()) == Hw2CF\Conf::I()->hw2cf_page_alias) {

        $uId = filter_input(INPUT_GET, "user");

        $uRole = Hw2CF\FormHelper::getUserFirstRole($uId);

        $contract = "";
        switch ($uRole) {
            case "hw2_partner":
                return "Content Partner Agreement";
            case "hw2_contributor":
                return "Media Distribution Agreement";
        }
    }
    
    return $data;
}

add_filter('the_title', 'hw2contracts_title');



/*
 * USER EVENTS
 */


/**
 * Mail to send on user role change
 */

function user_role_update($user_id, $new_role) {
    \Hw2CF\SysEvents::onRoleChange($user_id, $new_role);
}

function user_new_registration($user_id) {
    // currently no action
}

// disabled features
//add_action('set_user_role', 'user_role_update', 10, 2);
//add_action('user_register', 'user_new_registration', 10, 1);

/**
 * Create user roles ( We should limit this execution "on install" 
 */

 add_role('hw2_client', 'Agent', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false,
));

add_role('hw2_contributor', 'Contributor', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false,
));

add_role('hw2_partner', 'Partner', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false,
));

