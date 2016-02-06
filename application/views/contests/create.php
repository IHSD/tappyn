<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
<!-- Innerpages Content -->
<section class="innerpage">
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Launch A Contest</h2>
                    <?php echo form_open("contests/create");?>
                      <div class='form-row'>
                          Title
                          <?php echo form_input('title'); ?>
                      </div>
                      <div class='form-row'>
                          Describe what your oganization does and its target audience
                          <?php echo form_textarea("audience_description"); ?>
                      <div class='form-row'>
                          Tell Us How Your Different
                          <?php echo form_textarea('how_your_different'); ?>
                      </div>
                      <div class='form-row'>
                          Medium
                          <?php echo form_dropdown('platform', $platforms, '', array('id' => 'platform_dropdown')); ?>
                      </div>
                      <div class='form-row'>
                          Objective
                          <?php echo form_dropdown('objective', $objectives, '', array('id' => 'objective_dropdown')); ?>
                      </div>
                      <div class='form-row'>
                          Location
                          <?php echo form_input('location'); ?>
                      </div>
                      <div class='form-row'>
                          <label>Age</label>
                          <div>
                          <?php echo form_radio('age_range', '0', FALSE); ?>
                          <?php echo form_label('18-24', 'age_range'); ?>
                          <?php echo form_radio('age_range', '1', FALSE); ?>
                          <?php echo form_label('25-34', 'age_range'); ?>
                          <?php echo form_radio('age_range', '2', FALSE); ?>
                          <?php echo form_label('35-44', 'age_range'); ?>
                        </div>
                      </div>
                      <div class='form-row'>
                          <label>Gender</label>
                          <div>
                          <?php echo form_radio('gender', '0', FALSE); ?>
                          <?php echo form_label('All', 'gender'); ?>
                          <?php echo form_radio('gender', '1', FALSE); ?>
                          <?php echo form_label('Men', 'gender'); ?>
                          <?php echo form_radio('gender', '2', FALSE); ?>
                          <?php echo form_label('Women', 'gender'); ?>
                        </div>
                      </div>
                      <div class='div-center'<div class='form-row'><?php echo form_submit('submit', 'Submit', array("class" => 'btn btn-contest'));?></div>
                      <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('templates/footer'); ?>
<script>
$(document).ready(function() {
    // Set on page load
    // Change when medium is changed
    $('#platform_dropdown').change(function() {
        alert('dropdown changed');
        console.log($('#platform_dropdown').find(':selected').text());
        if($('#platform_dropdown').find(":selected").text() == 'General')
        {
            var new_option = ("<option value='brand_positioning'>Brand Positioning</option>");
            // Remove the send to website and add brand positioning
            $('#objective_dropdown').find('option[value="website_clicks"]').remove();
            $('#objective_dropdown').append(new_option);
        } else {
            // Remove the brand positioning and add website clicks
            var new_option = ("<option value='website_clicks'>Website Clicks</option>");
            // Remove the send to website and add brand positioning
            $('#objective_dropdown').find('option[value="brand_positioning"]').remove();
            $('#objective_dropdown').append(new_option);
        }
    });
})
</script>
