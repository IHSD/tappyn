
<!-- Innerpages Content -->
<section class="innerpage">
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Register a new account</h2>
                    <?php echo form_open("auth/create_user");?>
                      <div class="form-row">
                        <div class="medium-6 small-12 columns">
                              <?php echo lang('create_user_fname_label', 'first_name');?>
                              <?php echo form_input($first_name);?>
                        </div>
                        <div class="medium-6 small-12 columns">
                              <?php echo lang('create_user_lname_label', 'last_name');?>
                              <?php echo form_input($last_name);?>
                        </div>
                      </div>
                      
                          
                      <div class="form-row">
                        <div class="medium-6 small-12 columns">
                              <?php echo lang('create_user_password_label', 'password');?>
                              <?php echo form_input($password);?>
                        </div>
                        <div class="medium-6 small-12 columns">
                              <?php echo lang('create_user_password_confirm_label', 'password_confirm');?>
                              <?php echo form_input($password_confirm);?>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="medium-6 small-12 columns">
                              <?php echo lang('create_user_email_label', 'email');?>
                              <?php echo form_input($email);?>
                        </div>
                        <div class="medium-6 small-12 columns">
                          <div class="form-row">
                            <div>
                              <?php echo form_radio('group_id', '2', TRUE); ?>
                              <?php echo form_label('I want to write an ad', 'group_id'); ?>
                            </div>
                          </div>
                          <div class="form-row">
                            <div>
                              <?php echo form_radio('group_id', '3', FALSE); ?>
                              <?php echo form_label('I want to create a contest', 'group_id'); ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class='div-center'<p><?php echo form_submit('submit', lang('create_user_submit_btn'), array("class" => 'btn btn-contest'));?></p>

                      <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</section>
