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
                              <li role="presentation" class="active"><a href="#">Payouts</a></li>
                              <li role="presentation"><a href="<?php echo base_url().'admin/users/account/'.$user->id; ?>">Account Details</a></li>
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
                        <?php if(empty($payouts)): ?>
                            <div class='alert alert-warning'>This user does not have any payouts yet</div>
                        <?php else: ?>
                            <?php foreach($payouts as $payout): ?>
                                <div class=' row payout-table-row'>
                                    <div class='col-sm-4'>
                                        <h4>Payout</h4>
                                        <table class='table table-bordered table-condensed'>
                                            <tr>
                                                <td>Created</td>
                                                <td><?php echo date('D M d', strtotime($payout->created_at)); ?>
                                            </tr>
                                            <tr>
                                                <td>Amount</td>
                                                <td>$<?php echo ($payout->amount / 100); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Contest</td>
                                                <td><a href="#"><?php echo $payout->contest_id; ?><span class='a-label'>&nbsp;&nbsp;(Click to view)</a></td>
                                            </tr>
                                            <tr>
                                                <td>Submission</td>
                                                <td><a href="#"><?php echo $payout->submission_id; ?><span class='a-label'>&nbsp;&nbsp;(Click to view)</a></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <h4>Claim Data</h4>
                                    <div class='col-sm-4'>
                                        <?php if($payout->claimed == 0): ?>
                                            <div class='alert alert-warning'>
                                                Payout still pending
                                            </div>
                                        <?php else: ?>
                                            <table class='table table-bordered table-condensed'>
                                                <tr>
                                                    <td>Claimed</td>
                                                    <td><?php echo date('D M d', strtotime($payout->claimed_at)); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Account</td>
                                                    <td><?php echo $payout->account_id; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Transfer</td>
                                                    <td><?php echo $payout->transfer_id; ?></td>
                                                </tr>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                    <h4>Notes</h4>
                                    <div class='col-sm-4'>
                                        <form class='form-horizontal' method='post' action="<?php echo base_url().'admin/notes/'.$payout->id; ?>">
                                            <textarea class='form-control' rows='6' value="<?php echo $payout->notes; ?>"></textarea>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
<?php $this->load->view('templates/footer'); ?>
