
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
                      <div class='form-row'><?php echo form_input(array('name' => 'first_name','value' => '','placeholder' => 'First Name', 'type' => 'text'));?></div>
                      <div class='form-row'>
                           <?php echo form_input(array('name' => 'last_name','value' => '','placeholder' => 'Last Name', 'type' => 'text'));?>
                      </div>
                       <div class='form-row'>
                           <?php echo form_input(array('name' => 'email','value' => '','placeholder' => 'Email', 'type' => 'text'));?>
                      </div>
                      <div class='form-row'><?php echo form_input(array('name' => 'password','value' => '','placeholder' => 'Password', 'type' => 'password'));?>
                      </div>

                      <div class='form-row'><?php echo form_input(array('name' => 'password_confirm','value' => '','placeholder' => 'Password Confirm', 'type' => 'password'));?>
                     
                      </div>

                      <div class="form-row">
                        <div>
                          <?php echo form_radio('group_id', '2', TRUE); ?>
                          <?php echo form_label('I want to write an ad', 'group_id'); ?>
                        </div>
                        <div>
                          <?php echo form_radio('group_id', '3', FALSE); ?>
                          <?php echo form_label('I want to create a contest', 'group_id'); ?>
                        </div>
                      </div>

                      <div class='div-center'><?php echo form_submit('submit', lang('create_user_submit_btn'), array("class" => 'btn'));?></div>

                      <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('templates/footer'); ?>
