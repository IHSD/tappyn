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
                              <li role="presentation" class="active"><a href="#">Payment History</a></li>
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
                    <?php if($customer): ?>
                        <h3><?php echo $customer->id; ?> <span class='a-label'>&nbsp;&nbsp;(created <?php echo date('D M d', $customer->created) ?>)</h3>
                        <hr>
                        <h4>Payment History</h4>

                            <?php if(empty($charges->data)): ?>
                                <div class='col-sm-6 col-sm-offset-3 alert alert-warning'>
                                    This company does not have any charges yet!
                                </div>
                            <?php else: ?>
                                <table class='table table-condensed table-bordered'>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Amount Refunded</th>
                                        <th>Created</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                    </tr>
                                    <?php foreach($charges->data as $charge): ?>
                                        <tr>
                                            <td>
                                                <div class="dropdown">
                                                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    Actions
                                                    <span class="caret"></span>
                                                  </button>
                                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <li><a href="#">View</a></li>
                                                    <li role="separator" class="divider"></li>
                                                    <li><a href="#">Deactivate</a></li>
                                                  </ul>
                                                </div>
                                            </td>
                                            <td><?php echo $charge->id; ?></td>
                                            <td><?php echo $charge->amount; ?></td>
                                            <td><?php echo $charge->amount_refunded; ?></td>
                                            <td><?php echo date("D M d", $charge->created); ?></td>
                                            <td><?php echo $charge->description; ?></td>
                                            <td>

                                            </td>
                                            <td><?php echo $charge->source->id; ?></td>
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
