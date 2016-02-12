<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class='innerpage'>
    <?php if($account->transfers_enabled === FALSE): ?>
        <?php echo form_open("users/account"); ?>

            <?php echo form_input(array('name' => 'last_name','value' => '', 'placeholder' => 'Last Name', 'type' => 'text'));?>
            <?php echo form_input(array('name' => 'dob_year', 'value' => '', 'placeholder' => '', 'type' => 'text'));?>
            <?php echo form_input(array('name' => 'dob_day', 'value' => '', 'placeholder' => '', 'type' => 'text'));?>
            <?php echo form_input(array('name' => 'dob_month', 'value' => '', 'placeholder' => '', 'type' => 'text'));?>
            <?php echo form_submit('submit', 'Verify My Identity'); ?>
        <?php echo form_close(); ?>
    <?php endif; ?>
    <?php echo json_encode($account); ?>
</section>
