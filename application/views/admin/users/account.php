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
                        <?php if($account): ?>
                            <?php if($account->transfers_enabled == FALSE): ?>
                            <div class='row'>
                                <div class='alert alert-warning text-center col-sm-8 col-sm-offset-2'>
                                    Account setup started, but transfers not enabled yet :: <?php echo json_encode($account->verification->fields_needed); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <h4>Account <?php echo $account->id; ?></h4>
                            <?php echo json_encode($account); ?>
                            <div class='col-sm-4'>
                                <table class='table table-bordered table-condensed'>
                                    <tr>
                                        <td>Country</td>
                                        <td><?php echo $account->country; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Default Currency</td>
                                        <td><?php echo $account->default_currency; ?></td>
                                    </tr>
                                    <?php foreach($account->legal_entity as $key => $value): ?>
                                        <?php var_dump($key); ?>
                                        <?php if(is_array($value) || is_object($value)): ?>
                                            <?php foreach($value as $k => $v): ?>
                                                <tr>
                                                    <td><?php echo $key.$k ?></td>
                                                    <td><?php echo $v ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td><?php echo $key; ?></td>
                                                <td><?php echo $value; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
