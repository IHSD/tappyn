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
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/account/'.$user->id; ?>">Account Details</a></li>
                          <?php else: ?>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/contests/'.$user->id; ?>">Contests</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/companies/payment_history/'.$user->id; ?>">Payment History</a></li>
                              <li role="presentation" class="active"><a href="<?php echo base_url().'admin/companies/account_details/'.$user->id; ?>">Account Details</a></li>
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
                    <?php if($customer): ?>
                        <h3><?php echo $customer->id; ?> <span class='a-label'>&nbsp;&nbsp;(created <?php echo date('D M d', $customer->created) ?>)</h3>
                        <hr>
                        <h4>Payment Methods</h4>

                            <?php if(empty($customer->sources->data)): ?>
                                <div class='col-sm-6 col-sm-offset-3 alert alert-warning'>
                                    This customer does not have any payment methods yet!
                                </div>
                            <?php else: ?>
                                <table class='table table-condensed table-bordered'>
                                    <tr>
                                        <td></td>
                                        <td>ID</td>
                                        <td>Type</td>
                                        <td>Brand</td>
                                        <td>Country</td>
                                        <td>Last 4</td>
                                        <td>Exp</td>
                                        <td>Funding</td>
                                        <td>Fingerprint</td>
                                    </tr>
                                    <?php foreach($customer->sources->data as $source): ?>
                                        <tr>
                                            <td></td>
                                            <td><?php echo $source->id; ?></td>
                                            <td><?php echo $source->object; ?></td>
                                            <td><?php echo $source->brand; ?></td>
                                            <td><?php echo $source->country; ?></td>
                                            <td><?php echo $source->last4; ?></td>
                                            <td><?php echo $source->exp_month.'/'.$source->exp_year; ?></td>
                                            <td><?php echo $source->funding; ?></td>
                                            <td><?php echo $source->fingerprint; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>

                    <?php else: ?>
                        <div class='col-sm-6 col-sm-offset-3 alert alert-warning'>
                            There is no customer data to show for this company
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<script>

<?php $this->load->view('templates/footer'); ?>
