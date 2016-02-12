<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<?php $countries = array(
    'US' => "United States",

); ?>
<section class='innerpage'>
    <?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
        <?php echo form_open("accounts/details"); ?>
            <?php echo form_input(array('name' => 'first_name', 'value' => '', 'placeholder' => 'First Name', 'type' => 'text')); ?>
            <?php echo form_input(array('name' => 'last_name','value' => '', 'placeholder' => 'Last Name', 'type' => 'text'));?>
            <?php echo form_input(array('name' => 'dob_year', 'value' => '', 'placeholder' => 'DOB Year', 'type' => 'text'));?>
            <?php echo form_input(array('name' => 'dob_day', 'value' => '', 'placeholder' => 'DOB Day', 'type' => 'text'));?>
            <?php echo form_input(array('name' => 'dob_month', 'value' => '', 'placeholder' => 'DOB Month', 'type' => 'text'));?>
            <?php echo form_checkbox(array('name'=>'stripe_tos', 'value' => 'accept', 'checked' => FALSE,)); ?>
            <?php echo form_dropdown('country', $countries, 'US'); ?>
            <?php echo form_submit('submit', 'Verify My Identity'); ?>
        <?php echo form_close(); ?>
</section>
