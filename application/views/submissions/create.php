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
