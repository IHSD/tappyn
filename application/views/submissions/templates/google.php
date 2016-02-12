
    <div class='form-row'>
       <?php echo form_input(
       	array('name' => 'headline',
       	'value' => '',
       	'placeholder' =>
       		($contest->objective == 'website_clicks' ? "*Creative header capturing what makes this business unique" :
       		($contest->objective == 'app_installs' ? "*Creative header capturing what makes this business unique" :
       		($contest->objective == 'engagement' ? "*Creative header capturing what makes this business unique" : "*Creative header capturing what makes this business unique"))),
       		'type' => 'text',
       		'id' => 'headline'));?>
       	<div class='input-count'><span id='headline_span'>0</span> of 25 characters</div>
    </div>
    <div class='form-row'>
       <?php echo form_textarea(
       	array('name' => 'text',
       	'value' => '',
       	'placeholder' =>
       		($contest->objective == 'website_clicks' ? "*Tell what the product will do for the customer in a clear, specific way" :
       		($contest->objective == 'app_installs' ? "*Tell what the product will do for the customer in a clear, specific way" :
       		($contest->objective == 'engagement' ? "*Tell what the product will do for the customer in a clear, specific way" : "*Tell what the product will do for the customer in a clear, specific way"))),
       	'type' => 'text',
       	'id' => 'text',
       	'rows' => "3"));?>
       	<div class='input-count'><span id='text_span'>0</span> of 250 characters</div>
    </div>


<script>
$('#headline').keyup(function(){
	var wordlength = $('#headline').val().length;
	$("#headline_span").html(wordlength);
});
$('#text').keyup(function(){
	var wordlength = $('#text').val().length;
	$("#text_span").html(wordlength);
});
</script>
