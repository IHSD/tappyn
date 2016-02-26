<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2><?php echo $user->email; ?></h2>
                <hr>
                <div class='col-sm-12'>
                    <div class='col-sm-10'>
                        <ul class="nav nav-tabs">
                          <li role="presentation" class="active"><a href="<?php echo base_url().'admin/users/show/'.$user->id; ?>">Profile</a></li>
                          <?php if($this->ion_auth->in_group(2, $user->id)): ?>
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/submissions/'.$user->id; ?>">Submissions</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/payouts/'.$user->id; ?>">Payouts</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/account/'.$user->id; ?>">Account Details</a></li>
                          <?php else: ?>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/contests/'.$user->id; ?>">Contests</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/payment_history/'.$user->id; ?>">Payment History</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/account_details/'.$user->id; ?>">Account Details</a></li>

                          <?php endif; ?>
                        </ul>
                    </div>
                    <div class='col-sm-2'>
                        <span style='float-right'>
                            <button class='btn btn-danger'>Deactivate</button>
                        </span>
                    </div>
                </div>
                <div class='inner-content'>
                    <div class='col-sm-12'>
                        <h3>Edit User Profile</h3>
                        <?php if($this->ion_auth->in_group(2, $user->id)): ?>
                        <form class='form-horizontal' action="<?php echo base_url().'users/edit_user' ?>" method="POST">
                            <div class='form-group'>
                                <label>First Name</label>
                                <input type='text' value="<?php echo $user->first_name; ?>" name='first_name' class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label>Last Name</label>
                                <input type='text' value="<?php echo $user->last_name; ?>" name='last_name' class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label>Email</label>
                                <input type='text' value="<?php echo $user->email; ?>" name='email' class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label>Phone</label>
                                <input type='text' value="<?php echo $user->phone; ?>" name='phone' class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label>Password</label>
                                <input type='text' value="<?php echo $user->phone; ?>" name='password' class='form-control'>
                            </div>
                            <div class='form-group'>
                                <label>Password Confirm</label>
                                <input type='text' value="<?php echo $user->phone; ?>" name='password_confirm' class='form-control'>
                            </div>
                            <button type='submit' class='btn btn-primary'>Update User</button>
                        </form>
                        <?php else: ?>
                        <form class='form-horizontal' action="<?php echo base_url().'companies/profile' ?>" method="POST">
                            <div class='row'>
                                <div class='col-sm-4'>
                                    <p>
                                        <label>Name</label>
                                        <input type='text' value="<?php echo $user->profile->name; ?>" name='name' class='form-control'>
                                    </p>
                                    <p>
                                        <label>Email</label>
                                        <input type='text' value="<?php echo $user->email; ?>" name='email' class='form-control'>
                                    </p>
                                    <p>
                                        <label>Company Email</label>
                                        <input type='text' value="<?php echo $user->profile->company_url; ?>" name='company_url' class='form-control'>
                                    </p>
                                    <p>
                                        <label>Company URL</label>
                                        <input type='text' value="<?php echo $user->profile->name; ?>" name='name' class='form-control'>
                                    </p>
                                    <p>
                                        <label>Facebook URL</label>
                                        <input type='text' value="<?php echo $user->profile->facebook_url; ?>" name='facebook_url' class='form-control'>
                                    </p>
                                    <p>
                                        <label>Twitter Handle</label>
                                        <input type='text' value="<?php echo $user->profile->twitter_handle; ?>" name='twitter_handle' class='form-control'>
                                    </p>
                                </div>
                                <div class='col-sm-8'>
                                    <p>
                                        <label>Mission</label>
                                        <textarea rows='4' class='form-control' name='mission'><?php echo $user->profile->mission; ?></textarea>
                                    </p>
                                    <p>
                                        <label>Summary</label>
                                        <textarea rows='4' class='form-control' name='summary'><?php echo $user->profile->summary; ?></textarea>
                                    </p>
                                    <p>
                                        <label>Different</label>
                                        <textarea rows='4' class='form-control' name='different'><?php echo $user->profile->different; ?></textarea>
                                    </p>
                                </div>
                            </div>
                            <button type='submit' class='btn btn-primary'>Update User</button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <div class='col-sm-4'>
                        <?php if($this->ion_auth->in_group($user->id)): ?>
                        <h4>Age : <?php echo $user->profile->age; ?></h4>
                        <h4>Gender : <?php echo $user->profile->gender; ?></h4>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
<script>

<?php $this->load->view('templates/footer'); ?>
