<?php

namespace Hw2CF;

require_once PATH_HW2CF_PLG . 'src/Form.controller.php';

class FormView {

    private $controller;
    private $model;
    private $userCompiled;

    /**
     * 
     * @param \Hw2CF\FormController $controller
     */
    public function __construct($controller) {
        $this->controller = $controller;
        $this->model = $controller->getModel();
    }

    public function getRender($data) {
        $this->userCompiled = FormHelper::getDataVal($data, "hw2cf_user_saved");

        $tmpl = FormHelper::findTemplateFile("hw2cf/forms/contract.php");
        if (!$tmpl)
            $tmpl = PATH_HW2CF_PLG . 'src/templates/forms/contract.php';

        ob_start();

        include $tmpl;

        return ob_get_clean();
    }

    public function getHead() {
        ob_start();
        ?>	
        <link rel="stylesheet" href="<?php echo URL_HW2CF_PLG ?>modules/jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo URL_HW2CF_PLG ?>src/css/style.css"></script>
        <!--<link rel="stylesheet" href="<?php echo URL_HW2CF_PLG ?>modules/bootstrap/css/bootstrap.min.css"></script>
        <script src="<?php echo URL_HW2CF_PLG ?>modules/bootstrap/js/bootstrap.min.js"></script>-->
        <?php
        return ob_get_clean();
    }

    public function printFields($type) {
        foreach (Opts::I()->hw2cf_fields[$type] as $field) {
            $this->printField($field);
        }
    }

    public function printField($field) {
        if ($field instanceof Field) {
            // FOR CUSTOM FIELDS
            if ($field->type == "custom") {
                if (isset($this->customTmpl)) {
                    echo $this->customTmpl;
                }
                
                return;
            }
            // STANDARD FIELDS
            ?>
            <fieldset>
                <p>
                    <label for="<?= $field->getFullAlias() ?>"><?= $field->title ?><?= ($field->isRequired ? "*" : "") ?></label>
                    <br>
                    <?php
                    switch ($field->type) {
                        case "textarea":
                            ?>
                            <textarea class="hw2cf-field" name="<?= $field->getFullAlias() ?>" 
                                      id="<?= $field->getFullAlias() ?>" type="<?= $field->type ?>" 
                                      <?= ($field->isRequired && !$this->isAdmin() ? 'class="required" required' : "") ?> 
                                      <?= $field->getAttr($this) ?>><?= $this->controller->getDataVal($field->getFullAlias()) ?>
                            </textarea>
                            <?php
                            break;
                        default:
                            ?>
                            <input class="hw2cf-field" name="<?= $field->getFullAlias() ?>" 
                                   value="<?= $this->controller->getDataVal($field->getFullAlias()) ?>" 
                                   id="<?= $field->getFullAlias() ?>" type="<?= $field->type ?>" 
                                   <?= ($field->isRequired && !$this->isAdmin() ? 'class="required" required' : "") ?> 
                                   <?= $field->getAttr($this) ?>>

                            <?php
                            break;
                    }
                    ?>
                </p>
            </fieldset>
            <?php
        }
    }

    public function getScripts() {
        ob_start();
        ?>	
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="<?php echo URL_HW2CF_PLG ?>modules/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo URL_HW2CF_PLG ?>modules/jSignature/libs/jSignature.min.js"></script>
        <script src="<?php echo URL_HW2CF_PLG ?>src/js/scripts.js"></script>
        <!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo URL_HW2CF_PLG ?>modules/jSignature/libs/flashcanvas.js"></script>
        <![endif]-->
        <?php
        return ob_get_clean();
    }

    public function hasUserCompiled() {
        return $this->userCompiled;
    }

    public function setUserCompiled($userCompiled) {
        $this->userCompiled = $userCompiled;
    }

    public function isAdmin() {
        return $this->controller->isAdmin();
    }

    /**
     * 
     * @return \Hw2CF\FormController
     */
    public function getController() {
        return $this->controller;
    }

}
