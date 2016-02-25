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
                                                <td><a href="<?php echo base_url().'#/contest/'.$payout->contest_id; ?>"><?php echo $payout->contest_id; ?><span class='a-label'>&nbsp;&nbsp;(Click to view)</a></td>
                                            </tr>
                                            <tr>
                                                <td>Submission</td>
                                                <td><?php echo $payout->submission_id; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <h4>Claim Data</h4>
                                    <div class='col-sm-4 text-center'>
                                        <?php if($payout->claimed == 0): ?>
                                            <div class='alert alert-warning'>
                                                Payout still pending
                                            </div>
                                            <button class='btn btn-primary' data-toggle="modal" data-target="#payPalModal" data-payout="<?php echo $payout->id; ?>" data-email="<?php echo $user->email; ?>">Pay with PayPal</button>
                                        <?php else: ?>
                                            <table class='table table-bordered table-condensed'>
                                                <tr>
                                                    <td>Account Type</td>
                                                    <td><?php echo $payout->account_type; ?></td>
                                                </tr>
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
                                                    <td>
                                                        <?php if($payout->account_type == 'stripe'): ?>
                                                        <a href="#" data-toggle='modal' data-target="#transferModal" data-account="<?php echo $payout->account_id; ?>" data-transfer="<?php echo $payout->transfer_id; ?>"><?php echo $payout->transfer_id; ?></a>
                                                        <?php else: ?>
                                                        <a href="#"><?php echo $payout->transfer_id; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                    <h4>Notes</h4>
                                    <div class='col-sm-4'>
                                        <form class='form-horizontal' method='post' action="<?php echo base_url().'admin/notes/'.$payout->id; ?>">
                                            <textarea class='form-control' rows='4' value="<?php echo $payout->notes; ?>"></textarea>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id='payPalModal'>
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <form class='form-horizontal' action="<?php echo base_url().'admin/payments/paid_out'; ?>" method="POST">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">PayPal Payment Details</h4>
              </div>
              <div class="modal-body">
                 <div class='row'>
                     <div class='col-sm-6 col-sm-offset-3'>
                        <div class='form-group'>
                            <label>Payment Email (if different)</label>
                            <input type='text' name='email' id='user_email' class='form-control'>
                        </div>
                        <div class='form-group'>
                            <label>Transaction ID</label>
                            <input type='text' name='transaction_id' id='user_transaction' class='form-control'>
                        </div>
                        <input type='hidden' name='user_id' id='user_id' value="<?php echo $user->id; ?>">
                        <input type='hidden' name='payout_id' id='payout_id'>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id='transferModal'>
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Stripe Transfer Details</h4>
              </div>
              <div class="modal-body">
                  <h3 id='temp_loader'>Loading....</h3>
                  <h3 id='transfer_name'></h3>
                  <table id='transfer_table' class='table table-condensed table-bordered'>
                      <tr>
                          <td>Amount</td>
                          <td id='transfer_amount'></td>
                      </tr>
                      <tr>
                          <td>Created</td>
                          <td id='transfer_created'></td>
                      </tr>
                      <tr>
                          <td>Description</td>
                          <td id='transfer_description'></td>
                      </tr>
                      <tr>
                          <td>Status</td>
                          <td id='transfer_status'></td>
                      </tr>
                      <tr>
                          <td>Payment</td>
                          <td id='transfer_payment'></td>
                      </tr>
                      <tr>
                          <td>Balance Transaction</td>
                          <td id='transfer_transaction'></td>
                      </tr>
                  </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

<script>
    $('#payPalModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('email');
        var pid = button.data('payout');
        var modal = $(this);
        modal.find('#user_email').val(recipient);
        modal.find('#payout_id').val(pid);
    })
    $('#transferModal').on('show.bs.modal', function(event) {
        var loading = true;
        var button = $(event.relatedTarget);
        var transfer = button.data('transfer');
        var account = button.data('account');
        $.ajax({
            type: "POST",
            url : "<?php echo base_url().'admin/transfers'; ?>",
            data : {'transfer_id' : transfer, 'account_id' : account},
            dataType : "JSON",
            success: function(response) {
                $('#temp_loader').hide();
                $('#transfer_table').show();
                $('#transfer_name').show();
                $('#transfer_name').text(transfer);
                console.log(response);
                var transfer_data = response.data.transfer;
                $('#transfer_amount').text(transfer_data.amount);
                $('#transfer_created').text(moment.unix(transfer_data.created).format('MMM DD, H:mm a'));
                $('#transfer_description').text(transfer_data.description);
                $('#transfer_status').text(transfer_data.status);
                $('#transfer_payment').text(transfer_data.destination_payment);
                $('#transfer_transaction').text(transfer_data.balance_transaction);
            }
        })
    })
</script>
<?php $this->load->view('templates/footer'); ?>
