
	<div class='form-row'>
       <?php echo form_input(array('name' => 'headline','value' => '','placeholder' => ($contest->objective == 'app_installs' ? "Creatively capture what makes this app unique" : "Creatively capture what makes this business unique"), 'type' => 'text'));?>
    </div>
    <div class='form-row'>
       <?php echo form_textarea(array('name' => 'text','value' => '','placeholder' => "Tell a bit more. Be Clear", 'type' => 'text'));?>
    </div>
