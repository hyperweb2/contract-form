<?php

namespace Hw2CF;

require_once PATH_HW2CF_PLG . 'modules/dompdf/dompdf_config.inc.php';
require_once PATH_HW2CF_PLG . 'src/Form.view.php';
require_once PATH_HW2CF_PLG . 'src/Form.model.php';

class FormController {

    private $view;
    private $model;
    private $uId = 0;
    private $data;

    /**
     *
     * @var boolean 
     */
    private $isAdmin;

    public function __construct() {
        $this->model = new FormModel();
        $this->view = new FormView($this);
    }

    public function init() {
        $this->uId = intval(filter_input(INPUT_GET, "user"));
        
        if (!$this->uId) {
            $user = get_user_by( 'login', get_query_var('friendly') );
            if ($user)
                $this->uId = $user->ID;
        }
        
        $userRole = FormHelper::getUserFirstRole();
        $this->isAdmin = $userRole == "administrator";
        $this->data = $this->model->loadData($this->uId);
        $user_info = get_userdata($this->uId);

        switch (filter_input(INPUT_POST, 'action')) {
            case 'Save':

                if (!$this->isAdmin) {
                    $hasSaved = FormHelper::getDataVal($this->data, "hw2cf_user_saved");
                    if (!empty($hasSaved)) {
                        // hey user, are you trying to re-save?
                        echo "Cannot resend post data!";
                    } else {
                        $this->model->storeData($this->uId, $_POST, $this->isAdmin);
                        // reload data after save
                        $this->data = $this->model->loadData($this->uId);

                        $message = "The user " . FormHelper::getUserFormName($this->data) . " has compiled the agreement form <br>"
                                . "Use this <a href='" . bloginfo("url") . '/' . Opts::I()->hw2cf_page_alias . "/?user=" . $this->uId . "'>link</a> for direct access<br>";

                        $email = Opts::I()->hw2cf_email;
                        $headers = array(
                            'Content-Type: text/html; charset=UTF-8',
                            'From: ' . Opts::I()->company_name . ' <' . $email . '>',
                                //'Reply-To: ' . $email
                        );
                        $roleName = FormHelper::getRoleDisplayName($userRole);
                        $subject = "< $user_info->user_email > New $roleName has signed agreement";

                        wp_mail($email, $subject, $message, $headers);
                    }
                } else {
                    $this->model->storeData($this->uId, $_POST, $this->isAdmin);
                    // reload data after save
                    $this->data = $this->model->loadData($this->uId);
                }

                break;
            case 'Generate PDF':
                $template = new PdfTemplate($this->data);


                $uRole = FormHelper::getUserFirstRole($this->uId);

                $html = $template->getTemplate();

                /*
                  // test document with html
                  @unlink(PATH_HW2CF_PLG . "sample.html");
                  $htmlFile = fopen(PATH_HW2CF_PLG . "sample.html", "w") or die("Unable to open file!");
                  fwrite($htmlFile, $html);
                  fclose($htmlFile);
                 */

                $dompdf = new \DOMPDF();
                $dompdf->load_html($html);
                $dompdf->render();

                $roleName = FormHelper::getRoleDisplayName($uRole);
                $uName = FormHelper::getUserFormName($this->data);
                $email = Opts::I()->hw2cf_email;

                $fileName = FormHelper::sanitizeFileName("MDA_$uName.pdf");

                // REPLACE
                //@unlink(PATH_HW2CF_PLG . $fileName);
                //@unlink(PATH_HW2CF_PLG . "user-sign.png");
                //@unlink(PATH_HW2CF_PLG . "prop-sign.png");

                $pdfFile = fopen(PATH_HW2CF_PLG . $fileName, "w") or die("Unable to open file!");
                fwrite($pdfFile, $dompdf->output());
                fclose($pdfFile);

                
                $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . Opts::I()->hw2cf_company_name . ' <' . $email . '>',
                //'Reply-To: ' . $email
                );
                $message = "New Contract has been signed."
                . "<br>Attached is a PDF"
                . "<br><br> $fileName"
                . "<br>Generated pdf related to <a href='".bloginfo("url").'/'.Opts::I()->hw2cf_page_alias."/?user=" . $this->uId . "'>this</a> agreement form ";

                $subject = "< $user_info->user_email > Generated PDF for new $roleName ( $uName )";

                wp_mail($email, $subject, $message, $headers, PATH_HW2CF_PLG . $fileName);


                // KEEP CLEAN
                unlink(PATH_HW2CF_PLG . $fileName);
                unlink(PATH_HW2CF_PLG . "user-sign.png");
                unlink(PATH_HW2CF_PLG . "prop-sign.png");
                break;
            case "Delete data":
                $this->model->deleteData($this->uId);
                // reload data after delete
                $this->data = $this->model->loadData($this->uId);
                break;
            default:
                //invalid action
                breaK;
        }

        $allowed_roles = array("subscriber");
        if (//  if user exists
                FormHelper::userIdExists($this->uId) &&
                // end
                (
                // is admin
                $this->isAdmin
                // or the same user
                || ( is_user_logged_in() && in_array($userRole, $allowed_roles) && get_current_user_id() == $this->uId)
                )
        ) {
            // then show the form
            echo $this->view->getRender($this->data);
        } else {
            echo 'What are you doing here?';
        }
    }

    function getUId() {
        return $this->uId;
    }

    /**
     * 
     * @return \Hw2CF\FormView
     */
    public function getView() {
        return $this->view;
    }

    /**
     * 
     * @return \Hw2CF\FormModel
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * 
     * @return boolean
     */
    function isAdmin() {
        return $this->isAdmin;
    }

    function getData() {
        return $this->data;
    }

    function getDataVal($key) {
        return FormHelper::getDataVal($this->data, $key);
    }

}
