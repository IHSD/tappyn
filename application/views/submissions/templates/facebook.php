<div class='facebook-form-wrapper'>
	<a id='exampler'>See Example</a>
    <a id='closer' class='hidden_submission'>Close</a>
    <img class='hidden_submission' id='example' src="<?php echo base_url().'public/img/facebook_example.png' ?>">
    <div class='form-row'>
       <?php echo form_input(
       	array('name' => 'headline',
       	'value' => '',
       	'placeholder' =>
       		($contest->objective == 'website_clicks' ? "Highlight what makes this business / product unique" :
       		($contest->objective == 'app_installs' ? "Grab people's attention with what makes this app unique" :
       		($contest->objective == 'engagement' ? "Highlight what makes this business / product unique" : "Enter a headline"))),
       		'type' => 'text',
       		'id' => 'headline'));?>
       	<div class='input-count'><span id='headline_span'>0</span> of 25 characters</div>
    </div>
    <div class='form-row'>
       <?php echo form_textarea(
       	array('name' => 'text',
       	'value' => '',
       	'placeholder' =>
       		($contest->objective == 'website_clicks' ? "Describe why people should visit this website!" :
       		($contest->objective == 'app_installs' ? "Describe why people should install this app!" :
       		($contest->objective == 'engagement' ? "Create compelling content this business could supply" : "Tell a bit more. Be Clear!"))),
       	'type' => 'text',
       	'id' => 'text',
       	'rows' => "3"));?>
       	<div class='input-count'><span id='text_span'>0</span> of 250 characters</div>
    </div>
</div>
<style>
#exampler, #closer{
	text-decoration: underline;
	cursor:pointer;
	padding-bottom:10px;
}
#example{
	margin-bottom:5px;
}
</style>
<script>
$('#headline').keyup(function(){
	var wordlength = $('#headline').val().length;
	$("#headline_span").html(wordlength);
});
$('#text').keyup(function(){
	var wordlength = $('#text').val().length;
	$("#text_span").html(wordlength);
});

$('#exampler').click(function(){
	$("#exampler").addClass('hidden_submission');
	$("#closer").removeClass('hidden_submission');
	$("#example").removeClass('hidden_submission');
})
$('#closer').click(function(){
	$("#exampler").removeClass('hidden_submission');
	$("#closer").addClass('hidden_submission');
	$("#example").addClass('hidden_submission');
})
</script>
