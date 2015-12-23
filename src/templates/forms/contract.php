<?php

namespace Hw2CF;

/* @var $formView \Hw2CF\FormView */
$formView = $this;
?>
<style>
    .hw2cf-field {
        width: 50%;
    } 
</style>
<div id="hw2cf-form-container">
    <form id="hw2cf-form" action="" method="POST">
        <p><b>Please fill in your details below. These details will be used on the contract</b></p>
        <?php
        // PRINT USER FIELDS
        $formView->printFields("user");
        ?>

        <script type="text/javascript">
            $("#hw2cf_user_date").datepicker({dateFormat: 'dd/MM/yy'});
        </script>

        <!-- USER SIGNATURE -->
        <?php $field = Opts::I()->getField("user", "signature"); ?>
        <fieldset>
            <p>
                <label for="<?= $field->getFullAlias() ?>"><?= $field->title ?><?= ($field->isRequired ? "*" : "") ?></label>
                <input name="<?= $field->getFullAlias() ?>" value="<?= $formView->getController()->getDataVal($field->getFullAlias()) ?>" id="<?= $field->getFullAlias() ?>" type="hidden" 
                <?php
                $canEditUserSign=$formView->getController()->getUId() != get_current_user_id(); 
                $field->getAttr($formView);
                if ($canEditUserSign)
                    echo " readonly";
                ?>/>
            <div id="<?= $field->getFullAlias() ?>_box" class="signature-box" style="
            <?php
            if ($formView->hasUserCompiled() || !$canEditUserSign) {
                echo "pointer-events: none;";
            }
            ?>"></div>

            <?php if (!$formView->hasUserCompiled()) { ?>
                <button type="button" onclick="Hw2CF.resetSignature('#<?= $field->getFullAlias() ?>_box');
                        return false;">reset</button>
                    <?php } ?>
            </p>
        </fieldset>
        <p><span style="color:red; font-style: italic">Once Signed, scroll down and click 'save'</span></p>
        <script type="text/javascript">
            Hw2CF.initSignature("hw2cf_user_signature");

            $("#hw2cf-form").submit(function () {
                // another protection against re-sign
                if (!$('#hw2cf_user_signature').is('[readonly]')) {
                    Hw2CF.submitSign("hw2cf_user_signature");
                }
            });
        </script>

        <br>
        <br>
        <br>
        <h2> <?= Opts::I()->hw2cf_name ?> Fields </h2>

        <?php
        $formView->printFields("prop");



        // DO NOT SHOW ADMIN SIGNATURE ON USER SESSION
        if ($formView->isAdmin()) {
            $field = Opts::I()->getField("prop", "signature");
            ?>
            <fieldset>
                <p>
                    <label for="<?= $field->getFullAlias() ?>"><?= $field->title ?><?= ($field->isRequired ? "*" : "") ?></label>
                    <input name="<?= $field->getFullAlias() ?>" value="<?= $formView->getController()->getDataVal($field->getFullAlias()) ?>" id="<?= $field->getFullAlias() ?>" type="hidden"  
                    <?= $field->getAttr($formView) ?>
                           />
                <div id="<?= $field->getFullAlias() ?>_box" class="signature-box"></div>

                <button type="button" onclick="Hw2CF.resetSignature('#<?= $field->getFullAlias() ?>_box');return false;">reset</button>
                </p>
            </fieldset>
            <script type="text/javascript">
                Hw2CF.initSignature("hw2cf_prop_signature");

                $("#hw2cf-form").submit(function () {
                    Hw2CF.submitSign("hw2cf_prop_signature");
                });
            </script>

            <?php
        }
        ?>
        <br>
        <br>
        <br>


        <p>
            <?php
            // show save button only if it's admin or user has not signed yet 
            if (!$formView->isAdmin() || !$formView->hasUserCompiled()) {
                ?>
                <input type="submit" name="action" value="Save" />
            <?php } ?>
            <?php if ($formView->isAdmin()) { ?>
                <input type="submit" name="action" value="Generate PDF"/>
                <input type="submit" name="action" value="Delete data"/>
            <?php } ?>
        </p>
    </form>
</div>


