<?php

namespace Hw2CF;

require_once PATH_HW2CF_PLG . 'src/Form.controller.php';

class FormModel {

    public function storeData($userId, $data, $isAdmin) {
        foreach (Opts::I()->hw2cf_fields["user"] as $field) {
            if ($field instanceof Field) {
                update_user_meta($userId, $field->getFullAlias(), $data[$field->getFullAlias()]);
            }
        }

        // store flag about the action of user saving
        // in this way we can check that 
        // the user does not change data
        if (!$isAdmin) {
            update_user_meta($userId, 'hw2cf_user_sign_date', FormHelper::getCurrentDate());
            update_user_meta($userId, 'hw2cf_user_saved', true);
        } else {

            foreach (Opts::I()->hw2cf_fields["prop"] as $field) {
                if ($field instanceof Field) {
                    update_user_meta($userId, $field->getFullAlias(), $data[$field->getFullAlias()]);
                }
            }
        }
    }

    public function loadData($userId) {
        return get_user_meta($userId);
    }

    public function deleteData($userId) {
        // USER INFO
        foreach (Opts::I()->hw2cf_fields["user"] as $field) {
            if ($field instanceof Field) {
                delete_user_meta($userId, $field->getFullAlias());
            }
        }
        
        delete_user_meta($userId, 'hw2cf_user_sign_date');
        delete_user_meta($userId, 'hw2cf_user_saved');
        
        // PROPONENT
        foreach (Opts::I()->hw2cf_fields["prop"] as $field) {
            if ($field instanceof Field) {
                delete_user_meta($userId, $field->getFullAlias());
            }
        }
    }

}
