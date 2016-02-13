
<div class='form-row'>
   <?php echo form_textarea(
   array('name' => 'text','value' => '',
   'placeholder' => 
        ($contest->objective == 'app_installs' ? "Use wit & humor to capture what makes this app unique" : 
        ($contest->objective == 'website_clicks' ? "Use wit & humor to capture what makes this business unique" : 
        ($contest->objective == 'engagement' ? "Create compelling content this business could supply" :
        "Create a captivating tweet"))), 'type' => 'text', 'id' => 'text', 'rows' => '2'));?>
</div>
<div class='input-count'><span id='text_span'>0</span> of 140 characters</div>


<script>

$('#text').keyup(function(){
    var wordlength = $('#text').val().length;
    $("#text_span").html(wordlength);
});
</script>