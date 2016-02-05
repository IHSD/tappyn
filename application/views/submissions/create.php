<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<!-- Innerpages Content -->
<section class="innerpage">
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Submit An Ad</h2>
                    <?php echo form_open("submissions/create/{$contest->id}");?>
                      <div class='div-center'<p><?php echo form_submit('submit', 'Submit', array("class" => 'btn btn-contest'));?></p>
                      <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Here's your block for pulling in dynamic forms.
Put it wherever in the page you want. The array of fields generated
are accessible inside those templates using the $fields variable; -->
<?php switch($contest->platform): ?>
<?php case 'facebook': $this->load->view('submissions/templates/facebook', array('fields' => $fields)); break; ?>
<?php case 'google': $this->load->view('submissions/templates/google', array('fields' => $fields)); break; ?>
<?php case 'trending': $this->load->view('submissions/templates/trending', array('fields' => $fields)); break; ?>
<?php case 'tagline': $this->load->view('submissions/templates/tagline', array('fields' => $fields)); break; ?>
<?php case 'general': $this->load->view('submissions/templates/general', array('fields' => $fields)); break; ?>
<?php endswitch; ?>
<!-- end of templater -->
