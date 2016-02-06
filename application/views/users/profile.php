<?php defined("BASEPATH") or exit("No direct script access allowed"); ?>


<section class='innerpage'>
	<div class="medium-6 medium-offset-3 small-12">
		<h2 class='text-center'>Profile</h2>
		<?php $this->load->view('templates/notification', array(
			'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
			'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
		)); ?>
		<?php if($this->ion_auth->in_group(3)): ?>
			<h3>Company</h3>
		<?php elseif($this->ion_auth->in_group(2)) : ?>
			<h3>User</h3>
			<?php echo form_open("auth/create_user");?>
			<div class='form-row'><?php echo form_input(array('name' => 'first_name','value' => '','placeholder' => 'First Name', 'type' => 'text'));?></div>
                      
			<?php echo form_close(); ?>
		<?php endif; ?>
	</div>
</section>
<?php $this->load->view('templates/footer'); ?>

