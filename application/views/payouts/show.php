<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
<?php echo form_open("payouts/claim/{$payout->id}"); ?>
    <?php echo form_input(array('name' => 'source_id', 'type' => 'text')); ?>
    <?php echo form_submit("submit", "Claim Payout"); ?>
<?php echo form_close(); ?>
