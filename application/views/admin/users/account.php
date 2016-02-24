<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2><?php echo $user->email; ?></h2>
                <hr>
                <div class='col-sm-12'>
                    <div class='col-sm-10'>
                        <ul class="nav nav-tabs">
                          <li role="presentation"><a href="<?php echo base_url().'admin/users/show/'.$user->id; ?>">Profile</a></li>
                          <?php if($this->ion_auth->in_group(2, $user->id)): ?>
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/submissions/'.$user->id; ?>">Submissions</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/payouts/'.$user->id; ?>">Payouts</a></li>
                              <li role="presentation" class="active"><a href="#">Account Details</a></li>
                          <?php else: ?>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/contests/'.$user->id; ?>">Contests</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/payment_methods/'.$user->id; ?>">Payment Methods</a></li>
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
                    <div class='col-sm-6 col-sm-offset-3'>
                        <?php $this->load->view('templates/notification', array(
                    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                    )); ?>
                    </div>
                    <div class='col-sm-12'>
                        <table class='table table-condensed table-bordered table-hover table-striped'>
                            <tr>
                                <th>Actions</th>
                                <th>Created At</th>
                                <th>Headline</th>
                                <th style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;'>Text</th>
                                <th>Contest Onwer</th>
                                <th>Contest Title</th>
                                <th>Contest Ends</th>
                                <th>Payout</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
