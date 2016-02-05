<!-- Innerpages Content -->
        <!-- Login -->
        <div class="login-wrap">
            <div class="login-page">
                <div class="row padding">
                    <div class="medium-6 small-12 div-center">
                       <div class="medium-6 small-12 div-center">
                         <?php echo form_open("auth/login");?>
                          <h2 class="inner-title text-center">Login</h2>
                            <div class="form-row">
                                  <?php echo form_input(array('name' => 'identity','value' => '','placeholder' => 'Email', 'type' => 'text'));?>
                            </div>
                            <div class="form-row">
                                <?php echo form_input(array('name' => 'password', 'value' => '', 'placeholder' => 'Password', 'type' => 'password'));?>
                            </div>
                            <div class="form-row">
                                <?php echo lang('login_remember_label', 'remember');?>
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                            </div>
                            <div class='form-row'><?php echo form_submit('submit', 'Login', array("class" => 'btn'));?></div>
                            <?php echo form_close();?>
                            <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>

                             <div class="button-box">
                                <a href="<?php echo base_url().'auth/facebook'; ?>">
                                    <img src="<?php echo base_url().'public/img/img-facebook.png' ?>">
                                </a>
                            </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>