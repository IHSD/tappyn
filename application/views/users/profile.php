<?php defined("BASEPATH") or exit("No direct script access allowed"); ?>


<section class='innerpage'>
<div class="medium-6 medium-offset-3 small-12">
	<h2 class='text-center'>Profile</h2>
	
<?php $this->load->view('templates/notification', array(
    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
	<p class='text-center'><?php echo $this->session->first_name; ?></p>
	<p class='text-center'><?php echo $this->session->last_name; ?></p>
	<p class='text-center'><?php echo $this->session->email; ?></p>
	
</div>
</section>
<?php $this->load->view('templates/footer'); ?>

