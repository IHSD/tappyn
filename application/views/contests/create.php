<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<!-- Innerpages Content -->
<section class="innerpage">
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Launch A Contest</h2>
                    <?php echo form_open("contests/create");?>
                      <p>
                            Time Length
                            <?php echo form_dropdown('time_length', $options); ?>
                      </p>
                      <p>
                          Submission Limit
                          <?php echo form_dropdown('submission_limit', $limits); ?>
                      </p>
                      <p>
                          Prize
                          <?php echo form_dropdown('prize', $prizes); ?>
                      </p>
                      <p>
                          Title
                          <?php echo form_input('title'); ?>
                      </p>

                      <p>
                          Objective
                          <?php echo form_input('objective'); ?>
                      </p>

                      <p>
                          Platform
                          <?php echo form_dropdown('platform', $platforms); ?>
                      </p>
                      <div class='div-center'<p><?php echo form_submit('submit', 'Login', array("class" => 'btn btn-contest'));?></p>
                      <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</section>
