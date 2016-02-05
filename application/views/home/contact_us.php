
        <!-- Contact Us -->
        <div class="contact-us">
            <div class="row padding">
                <div class="medium-6 small-12 div-center">
                    <h2 class="inner-title text-center">Contact Us</h2>
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
                <!--
                <div class="medium-8 div-center">
                    <div class="form-row">
                        <div class="medium-5 column">
                            <label>Are you a customer or a creative?</label>
                            <select>
                                <option>I am a customer</option>
                                <option>I am a creative</option>
                            </select>
                        </div>
                        <div class="medium-5 column">
                            <label>Your email address<span>*</span></label>
                            <input type="text">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="medium-5 column">
                            <label>Subject<span>*</span></label>
                            <input type="text">
                        </div>
                        <div class="medium-5 column">
                            <label>Choose a topic<span>*</span></label>
                            <select>
                                <option>-</option>
                                <option>Topic</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="medium-12 column">
                            <label>Subject<span>*</span></label>
                            <textarea></textarea>
                            <p>Please enter the details of your request. A member of our support staff will respond as soon as possible.</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="medium-12 column">
                            <button class="btn">SUBMIT</button>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
