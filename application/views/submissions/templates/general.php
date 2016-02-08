
<div class='form-row'>
   <?php echo form_textarea(
   	array('name' => 'text',
   	'value' => '',
   	'placeholder' => 
		($contest->objective == 'website_clicks' ? "Grab people's attention with what makes this business unique" : 
   		($contest->objective == 'app_installs' ? "Grab people's attention with what makes this app unique" : 
   		($contest->objective == 'engagement' ? "Grab people's attention with what makes this business unique" : "Enter a headline"))), 
   		 'type' => 'text'
   		 'rows' => 3));?>
</div>

<script>
$('#text').keyup(function(){
	var wordlength = $('#text').val().length;
	$("#text_span").html(wordlength);
});
</script>