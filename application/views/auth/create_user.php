
<!-- Innerpages Content -->
<section class="innerpage">
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Register a new account</h2>
                    <?php echo form_open("auth/create_user");?>
                      <p class="medium-6 small-12 columns">
                            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
                            <?php echo form_input($first_name);?>
                      </p>

                      <p class="medium-6 small-12 columns">
                            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
                            <?php echo form_input($last_name);?>
                      </p>
                      
                      <?php
                      if($identity_column!=='email') {
                          echo '<p class="medium-6 small-12 columns">';
                          echo lang('create_user_identity_label', 'identity');
                          echo '<br />';
                          echo form_error('identity');
                          echo form_input($identity);
                          echo '</p>';
                      }
                      ?>


                      <p class="medium-6 small-12 columns">
                            <?php echo lang('create_user_password_label', 'password');?> <br />
                            <?php echo form_input($password);?>
                      </p>

                      <p class="medium-6 small-12 columns">
                            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
                            <?php echo form_input($password_confirm);?>
                      </p>

                      <p class="medium-6 small-12 columns">
                            <?php echo lang('create_user_email_label', 'email');?> <br />
                            <?php echo form_input($email);?>
                      </p>

                      <div class="medium-6 small-12 columns">
                        <p>
                          <?php echo form_radio('group_id', '2', TRUE); ?>
                          <?php echo form_label('I want to write an ad', 'group_id'); ?>
                        </p>
                        <p>
                          <?php echo form_radio('group_id', '3', FALSE); ?>
                          <?php echo form_label('I want to create a contest', 'group_id'); ?>
                        </p>
                      </div>

                      <div class='div-center'<p><?php echo form_submit('submit', lang('create_user_submit_btn'), array("class" => 'btn btn-contest'));?></p>

                      <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</section>
