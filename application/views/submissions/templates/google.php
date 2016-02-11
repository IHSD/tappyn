
    <div class='form-row'>
       <?php echo form_input(
       	array('name' => 'headline',
       	'value' => '',
       	'placeholder' =>
       		($contest->objective == 'website_clicks' ? "*Captivating Headline Here" :
       		($contest->objective == 'app_installs' ? "*Captivating Headline Here" :
       		($contest->objective == 'engagement' ? "*Captivating Headline Here" : "Enter a headline"))),
       		'type' => 'text',
       		'id' => 'headline'));?>
       	<div class='input-count'><span id='headline_span'>0</span> of 25 characters</div>
    </div>
    <div class='form-row'>
       <?php echo form_textarea(
       	array('name' => 'text',
       	'value' => '',
       	'placeholder' =>
       		($contest->objective == 'website_clicks' ? "Clear Description of the Headline Here" :
       		($contest->objective == 'app_installs' ? "Clear Description of the Headline Here" :
       		($contest->objective == 'engagement' ? "Clear Description of the Headline Here" : "Tell a bit more. Be Clear!"))),
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
