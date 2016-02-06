<?php defined("BASEPATH") or exit("No direct script access allowed"); ?>


<section class='innerpage'>
	<div class="medium-6 medium-offset-3 small-12">
		<h2 class='text-center'>Profile</h2>
		<?php $this->load->view('templates/notification', array(
			'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
			'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
		)); ?>
		<?php if($this->ion_auth->in_group(3)): ?>
			<?php echo form_open("user/profile");?>
			<div class='form-row'>
				<label>Your company's name</label>
				<?php echo form_input(array('name' => 'company_name','value' => '','placeholder' => 'Company Name', 'type' => 'text'));?>
			</div>
			<div class='form-row'>
				<label>Your company's email so we can get ahold of you</label>
				<?php echo form_input(array('name' => 'company_email','value' => '','placeholder' => 'Company Email', 'type' => 'text'));?>
			</div>
			<div class='form-row'>
				<label>Tell us a bit about your company</label>
				<?php echo form_textarea(array('name' => 'mission','value' => '','placeholder' => 'Company info', 'type' => 'text'));?>
			</div>
			<div class='form-row'>
				<label>How is your company different from the rest?</label>
				<?php echo form_textarea(array('name' => 'extra_info','value' => '','placeholder' => 'Additional Info', 'type' => 'text'));?>
			</div>
			<div class='form-row'>
				<label>Your company's logo</label>
				<?php echo form_upload(array('name' => 'logo_url','value' => '','placeholder' => '', 'type' => 'file'));?>
			</div>
			<div class='form-row'>
				<label>Your company's website</label>
				<?php echo form_input(array('name' => 'company_url','value' => '','placeholder' => 'Company Website', 'type' => 'text'));?>
			</div> 
			<div class='form-row'>
				<label>Your company's Facebook page</label>
				<?php echo form_input(array('name' => 'facebook_url','value' => '','placeholder' => 'Facebook Page', 'type' => 'text'));?>
			</div>                     
			<div class='div-center'><?php echo form_submit('submit', 'Update Your Info', array("class" => 'btn'));?></div> 
			<?php echo form_close(); ?>
		<?php elseif($this->ion_auth->in_group(2)) : ?>
			<h3>User</h3>

		<?php endif; ?>
	</div>
</section>
<?php $this->load->view('templates/footer'); ?>

