<?php

namespace Hw2CF;

require_once PATH_HW2CF_PLG . 'modules/jSignature/extras/SignatureDataConversion_PHP/core/jSignature_Tools_Base30.php';

class FormHelper {

    public static function findTemplateFile($file) {
        if (file_exists(get_stylesheet_directory()."/".$file))
                return get_stylesheet_directory()."/".$file;
        else if (file_exists(get_template_directory()."/".$file))
                return get_template_directory()."/".$file;
        
        return NULL;
    }
    /**
     * 
     * @global \Hw2CF\WP_User $current_user
     * @param Integer $id : if no id is passed, then it get the $current_user
     * @return String
     */
    public static function getUserFirstRole($id = NULL) {
        if (!$id) {
            global $current_user;
        } else {
            $current_user = new \WP_User($id);
        }

        $user_roles = $current_user->roles;

        $user_role = array_shift($user_roles);

        return $user_role;
    }

    public static function hasRole($role, $id = NULL) {
        if (!$id) {
            global $current_user;
        } else {
            $current_user = new \WP_User($id);
        }

        if (!empty($current_user->roles) && is_array($current_user->roles)) {
            return in_array($role, $current_user->roles);
        }
    }

    public static function getUserFormName($data) {
        return FormHelper::getDataVal($data, "hw2cf_user_first_name") . " " . FormHelper::getDataVal($data, "hw2cf_user_last_name");
    }

    public static function getRoleDisplayName($role) {
        global $wp_roles;

        return $wp_roles->roles[$role]['name'];
    }

    public static function getRelativeEmail($plgOptions, $role) {
        return $plgOptions["email_" . $role];
    }

    public static function userIdExists($user) {

        global $wpdb;

        $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users WHERE ID = '$user'");

        return $count == 1;
    }

    public static function getCurrentDate() {
        $today = getdate();
        $month = $today['month'];
        $mday = $today['mday'];
        $year = $today['year'];
        return "$mday/$month/$year";
    }

    public static function getDataVal($array, $key) {
        if (isset($array[$key]) && isset($array[$key][0])) {
            return $array[$key][0]; // get the first val
        }
        
        return NULL;
    }

    public static function sanitizeFileName($filename) {
        $filename_raw = $filename;
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
        $special_chars = apply_filters('sanitize_file_name_chars', $special_chars, $filename_raw);
        $filename = str_replace($special_chars, '', $filename);
        $filename = preg_replace('/[\s-]+/', '-', $filename);
        $filename = trim($filename, '.-_');
        return apply_filters('sanitize_file_name', $filename, $filename_raw);
    }

    public static function signToImage($dbData, $outFile) {
// Get signature string from _POST
        $data = str_replace('data:image/jsignature;base30,', '', $dbData);

// Create jSignature object
        $signature = new \jSignature_Tools_Base30();

// Decode base30 format
        $a = $signature->Base64ToNative($data);

// Create a image            
        $im = imagecreatetruecolor(640, 280);

// Save transparency for PNG
        imagesavealpha($im, true);

// Fill background with transparency
        $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
        imagefill($im, 0, 0, $trans_colour);

// Set pen thickness
        imagesetthickness($im, 5);

// Set pen color to black            
        $black = imagecolorallocate($im, 0, 0, 0);

// Loop through array pairs from each signature word
        for ($i = 0; $i < count($a); $i++) {
            // Loop through each pair in a word
            for ($j = 0; $j < count($a[$i]['x']); $j++) {
                // Make sure we are not on the last coordinate in the array
                if (!isset($a[$i]['x'][$j]) or ! isset($a[$i]['x'][$j + 1]))
                    break;
                // Draw the line for the coordinate pair
                imageline($im, $a[$i]['x'][$j], $a[$i]['y'][$j], $a[$i]['x'][$j + 1], $a[$i]['y'][$j + 1], $black);
            }
        }

        if (file_exists($outFile))
            unlink($outFile);
        // Save image to a folder       
        if (!imagepng($im, $outFile)) // Removing $filename will output to browser instead of saving
            die("!! Error exporting signature !!");
        // Clean up
        imagedestroy($im);
    }

}
