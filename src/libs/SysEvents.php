<?php

namespace Hw2CF;

class SysEvents {

    public static function onRoleChange($user_id, $new_role) {
        $roles = array("hw2_contributor", "hw2_partner");
        if (in_array($new_role, $roles))
            return;

        $site_url = get_bloginfo('wpurl');
        $user_info = get_userdata($user_id);
        $to = $user_info->user_email;
        $subject = "Role changed: " . $site_url . "";
        $message = "Hello " . $user_info->display_name . " your role has changed on " . $site_url . ", congratulations you are now an " . $new_role . "<br>"
                . "You can change your profile information here: "; // add link to profile
        wp_mail($to, $subject, $message);
    }

}
