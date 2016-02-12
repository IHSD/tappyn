
    <!-- Login -->
    <div class="login-wrap">
        <div class="login-page signup">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Register a new account</h2>
                    <?php $this->load->view('templates/notification', array(
    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>

                    <?php echo form_open("auth/create_user");?>
                      <div class='small-12 columns'>
                      <div class='form-row'>
                        <?php echo form_input(array('name' => 'name','value' => '','placeholder' => 'Full Name', 'type' => 'text'));?>
                      </div></div>
                      <div class='small-12 columns'>
                      <div class='form-row'>
                           <?php echo form_input(array('name' => 'location','value' => '','placeholder' => 'Location', 'type' => 'text'));?>
                      </div></div>
                      <div class='small-6 columns'>
                        <div class='form-row'>
                            <?php echo form_dropdown('age', $ages, 'AGES'); ?>
                        </div>
                      </div>
                      <div class='small-6 columns'>
                        <div class='form-row'>
                            <?php echo form_dropdown('gender', $genders, 'GENDER'); ?>
                        </div>
                      </div>
                      <div class='small-12 columns'>
                      <div class='form-row'>
                           <?php echo form_input(array('name' => 'email','value' => '','placeholder' => 'Email', 'type' => 'email'));?>
                      </div></div>
                      <div class='small-12 columns'>
                      <div class='form-row'><?php echo form_input(array('name' => 'password','value' => '','placeholder' => 'Password', 'type' => 'password'));?>
                      </div></div>

                      <div class='small-12 columns'>
                      <div class='form-row'><?php echo form_input(array('name' => 'password_confirm','value' => '','placeholder' => 'Password Confirm', 'type' => 'password'));?>
                      </div></div>
                      <div class='small-12 columns'>
                      <div class="form-row">
                          <?php echo form_dropdown('group_id', array('2' => 'I want to write content', '3' => "I want to create contests"));?>
                      </div></div>

                      <div class='small-12 columns'><div class='form-row div-center'><?php echo form_submit('submit', 'Register', array("class" => 'btn large-6 small-12 large-offset-3'));?></div></div>
                      <?php echo form_close();?>
                      <div class='text-center' style='padding-bottom:20px'>or</div>
                      <div class="large-6 large-offset-3 small-12 button-box register-on-facebook" style='margin-bottom:20px;'>
                         <a href="<?php echo base_url().'auth/facebook'; ?>">
                             <img src="<?php echo base_url().'public/img/img-facebook.png' ?>">
                         </a>
                     </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('templates/footer'); ?>
