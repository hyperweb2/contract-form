<?php

/**
 * 
 * CF7 MODS
 */
// create user from CF7

function create_user_from_registration($cfdata) {
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        // Contact Form 7 version 3.9 removed $cfdata->posted_data and now
        // we have to retrieve it from an API
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        // For pre-3.9 versions of Contact Form 7
        $formdata = $cfdata->posted_data;
    } else {
        // We can't retrieve the form data
        return $cfdata;
    }
    // Check this is the user registration form
    switch ($cfdata->id()) {
        case 268:
            $ruolo = "hw2_client";
            break;
        case 716:
            $ruolo = "hw2_contributor";
            break;
        case 733:
            $ruolo = "hw2_partner";
            break;
        default:
            $ruolo = "subscriber";
            break;
    }

    $password = wp_generate_password(12, false);
    $email = $formdata['Email'];
    $first = $formdata['First'];
    $last = $formdata['Last'];
    // Construct a username from the user's name
    $username = strtolower(str_replace(' ', '', $first));
    if (!email_exists($email)) {
        // Find an unused username
        $username_tocheck = $username;
        $i = 1;
        while (username_exists($username_tocheck)) {
            $username_tocheck = $username . $i++;
        }
        $username = $username_tocheck;
        // Create the user
        $userdata = array(
            'user_login' => $username,
            'user_pass' => $password,
            'user_email' => $email,
            'nickname' => $first,
            'display_name' => $first,
            'first_name' => $first,
            'last_name' => $last,
            'role' => $ruolo
        );
        $user_id = wp_insert_user($userdata);
    }

    return $cfdata;
}

function user_reg_cf() {
    
}

add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1);


add_filter('wpcf7_validate_text', 'hw2contracts_validation_filter_func', 10, 2);
add_filter('wpcf7_validate_text*', 'hw2contracts_validation_filter_func', 10, 2);

function hw2contracts_validation_filter_func($result, $tag) {
    // Email check
    // if already in database, invalidate
    $user = get_user_by('email', $_POST["Email"]);
    if ($user != false)
        $result->invalidate('Email', 'Your email already exists in our database');

    $countries = $_POST['BeemCountries'];

    if ($countries === 'Other please state below') {
        $value = isset($_POST["CountryBelow"]) ? trim(wp_unslash(strtr((string) $_POST["CountryBelow"], "\n", " "))) : '';

        if (strlen($value) === 0) {
            $result->invalidate("CountryBelow", 'Please add Country');
        }
    }

    $credited = $_POST['Credited'];

    if ($credited === 'Yes') {
        $value = isset($_POST["CreditedBelow"]) ? trim(wp_unslash(strtr((string) $_POST["CreditedBelow"], "\n", " "))) : '';

        if (strlen($value) === 0) {
            $result->invalidate("CreditedBelow", 'Please add credit');
        }
    }

    return $result;
}