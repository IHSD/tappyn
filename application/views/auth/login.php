<!-- Innerpages Content -->
<section class="innerpage">
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Login</h2>
                    <?php echo form_open("auth/login");?>
                      <p>
                            <?php echo lang('login_identity_label', 'identity');?>
                            <?php echo form_input($identity);?>
                      </p>
                      <p>
                          <?php echo lang('login_password_label', 'password');?>
                          <?php echo form_input($password);?>
                      </p>
                      <p>
                          <?php echo lang('login_remember_label', 'remember');?>
                          <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                      </p>
                      <div class='div-center'<p><?php echo form_submit('submit', 'Login', array("class" => 'btn btn-contest'));?></p>
                      <?php echo form_close();?>
                      <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
                </div>
            </div>
        </div>
    </div>
</section>
