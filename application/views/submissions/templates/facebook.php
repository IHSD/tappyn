
<div class='form-row'>
   <?php echo form_input(array('name' => 'headline','value' => '','placeholder' => ($contest->objective == 'website_clicks' ? "Grab People's Attention" : ($contest->objective == 'app_installs' ? "Creatively capture what makes this app unique" : ($contest->objective == 'engagement' ? "Headline for engagement" : "Enter a headline"))), 'type' => 'text'));?>
</div>
<div class='form-row'>
   <?php echo form_input(array('name' => 'link_explanation','value' => '','placeholder' => ($contest->objective == 'website_clicks' ? "Describe why people should visit this website" : ($contest->objective == 'app_installs' ? "Describe why people should install this app" : ($contest->objective == 'engagement' ? "Enter a headline" : "Enter a headeline"))), 'type' => 'text'));?>
</div>
<div class='form-row'>
   <?php echo form_textarea(array('name' => 'text','value' => '','placeholder' => ($contest->objective == 'website_clicks' ? "Tell a bit more. Be clear!" : ($contest->objective == 'app_installs' ? "Tell a bit more. Be clear!" : ($contest->objective == 'engagement' ? "Tell a bit more. Be clear!" : "Tell a bit more. Be Clear!"))), 'type' => 'text'));?>
</div>
