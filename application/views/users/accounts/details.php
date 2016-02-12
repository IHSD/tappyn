<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<?php $countries = array(
    'US' => "United States",

); ?>
<section class='innerpage'>
    <div class='medium-6 small-12 div-center'>
        <?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
        <?php echo form_open("accounts/details"); ?>
            <div class='small-6 columns'><div class='form-row'><?php echo form_input(array('name' => 'first_name', 'value' => '', 'placeholder' => 'First Name', 'type' => 'text')); ?></div></div>
            <div class='small-6 columns'><div class='form-row'><?php echo form_input(array('name' => 'last_name','value' => '', 'placeholder' => 'Last Name', 'type' => 'text'));?></div></div>
            <div class='small-4 columns'><div class='form-row'><?php echo form_input(array('name' => 'dob_year', 'value' => '', 'placeholder' => 'DOB Year', 'type' => 'text'));?></div></div>
            <div class='small-4 columns'><div class='form-row'><?php echo form_input(array('name' => 'dob_month', 'value' => '', 'placeholder' => 'DOB Month', 'type' => 'text'));?></div></div>
            <div class='small-4 columns'><div class='form-row'><?php echo form_input(array('name' => 'dob_day', 'value' => '', 'placeholder' => 'DOB Day', 'type' => 'text'));?></div></div>
            <div class='small-12 columns'><div class='form-row'><?php echo form_dropdown('country', $countries, 'US'); ?></div></div>
            <div class='small-12 columns'><div class='form-row div-center'>
                <?php echo form_submit('submit', 'Verify My Identity', array('class' => 'btn')); ?>
            </div></div>
        <?php echo form_close(); ?>
    </div>
</section>


<?php $this->load->view('templates/footer'); ?>
