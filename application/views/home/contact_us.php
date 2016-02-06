
        <!-- Contact Us -->
        <section class='innerpage'>
        <div class="contact-us">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Contact Us</h2>
                    <?php $this->load->view('templates/notification', array(
                        'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                        'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                    )); ?>
                    <?php echo form_open("welcome/contact_us");?>
                    <div class="form-row">
                          <?php echo form_dropdown('contact', array('' => 'Are you a customer or a creator?','company' => 'Customer','user' => 'Creator'), '');;?>
                    </div>
                    <div class="form-row">
                        <?php echo form_input(array('name' => 'email', 'value' => '', 'placeholder' => 'Please enter your email', 'type' => 'text'));?>
                    </div>
                    <div class="form-row">
                        <?php echo form_textarea(array('name' => 'details', 'value' => '', 'placeholder' => 'Please enter the details of your request. A member of our support staff will respond as soon as possible.', 'type' => 'text')); ?>
                    </div>
                    <div class='form-row'><?php echo form_submit('submit', 'Submit', array("class" => 'btn'));?></div>
                    <?php echo form_close();?>
                </div>
               
            </div>
        </div>
        </section>
        
<?php $this->load->view('templates/footer'); ?>
