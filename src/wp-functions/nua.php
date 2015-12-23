<?php

/**
 *  NEW USER APPROVEMENT MODS
 */
function hw2contracts_nua_custom_approve_user_subject($subject) {
    return "[Beem] Registration Approved:  Account Setup & Contract";
}

function hw2contracts_nua_custom_approve_user_message_hw2($message, $user) {
    $message = "Your account has been approved. Please login using the details below and complete the contract online:<br><br>";
    $message .= "{login_url}<br>";
    $message .= "{username}<br>";
    $message .= "{password}<br><br>";

    $role = \Hw2CF\FormHelper::getUserFirstRole($user->ID);

    if ($role == "hw2_contributor" || $role == "hw2_partner") {
        $message .= "After login, you will be automatically redirected to the Agreement Form<br>";
        $message .= "where you can fill your contract<br><br>";
        $message .= "This is the link to the agreement form: <a href='".bloginfo("url").'/'.Conf::$hw2cf_page_alias."/?user=" . $user->ID . "'>LINK</a> ( you must be logged ) ";
    }

    $message .= "The BEEM Team<br>";
    $message .= "info@hw2.io<br>";

    return $message;
}

function hw2contracts_nua_custom_request_approval_subject_hw2($subject, $username, $email) {
    $user = get_user_by('email', $email);
    $role = \Hw2CF\FormHelper::getUserFirstRole($user->ID);

    $subject = "< $email > New " . \Hw2CF\FormHelper::getRoleDisplayName($role) . "  Approval waiting";

    return $subject;
}

function hw2contracts_nua_custom_request_approval_message($message, $username, $email) {
    $user = get_user_by('email', $email);
    $newMessage = "Before approve letting the user to see the form maybe you want to fill Beem fields in its agreement<br>"
            . "Using <a href='".bloginfo("url").'/'.Conf::$hw2cf_page_alias."/?user=" . $user->ID . "'>this link</a><br><br>";

    $newMessage.=$message;

    return $newMessage;
}

function hw2contracts_nua_custom_html_email($headers) {
    $new_headers = array();
    foreach ($headers as $header) {
        $new_headers[] = str_replace('Content-Type: text/plain;', 'Content-Type: text/html;', $header);
    }

    return $new_headers;
}

function hw2contracts_nua_custom_email_admins_hw2($to, $user_email) {
    $user = get_user_by('email', $user_email);

    $role = \Hw2CF\FormHelper::getUserFirstRole($user->ID);

    if ($role == "hw2_contributor" || $role == "hw2_partner") {
        $to = array(\Hw2CF\Opts::I()->hw2cf_email);
    }

    return $to;
}

add_filter('new_user_approve_email_admins_hw2', 'hw2contracts_nua_custom_email_admins_hw2', 10, 2);
add_filter('new_user_approve_request_approval_message', 'hw2contracts_nua_custom_request_approval_message', 10, 3);
add_filter('new_user_approve_approve_user_message_default_hw2', 'hw2contracts_nua_custom_approve_user_message_hw2', 10, 2);
add_filter('new_user_approve_approve_user_subject', 'hw2contracts_nua_custom_approve_user_subject', 10, 1);
add_filter('new_user_approve_request_approval_subject_hw2', 'hw2contracts_nua_custom_request_approval_subject_hw2', 10, 3);
add_filter('new_user_approve_email_header', 'hw2contracts_nua_custom_html_email', 10, 2);