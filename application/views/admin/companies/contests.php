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
                              <li role="presentation" class="active"><a href="#">Contests</a></li>
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
                    <table class='table table-condensed table-bordered'>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Start Time</th>
                            <th>Stop Time</th>
                            <th>Platform</th>
                            <th>Objective</th>
                            <th>Created At</th>
                            <th>Status</th>
                        </tr>
                        <?php foreach($contests as $contest): ?>
                            <tr>
                                <td>
                                    <div class="dropdown">
                                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Actions
                                        <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li><a href="<?php echo base_url().'admin/contests/show/'.$contest->id; ?>">View</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#">Deactivate</a></li>
                                      </ul>
                                    </div>
                                </td>
                                <td><?php echo $contest->id; ?></td>
                                <td><?php echo $contest->title; ?></td>
                                <td><?php echo date('D M d H:i', strtotime($contest->start_time)); ?></td>
                                <td><?php echo date('D M d H:i', strtotime($contest->stop_time)); ?></td>
                                <td><?php echo $contest->platform; ?></td>
                                <td><?php echo $contest->objective; ?></td>
                                <td><?php echo date("D M d, H:i", strtotime($contest->created_at)); ?></td>
                                <td>
                                    <?php if($contest->paid == 0): ?>
                                        Not Paid
                                    <?php elseif($contest->start_time < date('Y-m-d H:i:s')): ?>
                                        Not started
                                    <?php elseif($contest->start_time > date('Y-m-d H:i:s') && $contest->stop_time < date('Y-m-d H:i:s')): ?>
                                        Running
                                    <?php elseif($contest->stop_time < date('Y-m-d H:i:s')): ?>
                                        Completed
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
<script>

<?php $this->load->view('templates/footer'); ?>
