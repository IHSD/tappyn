<?php defined("BASEPATH") or exit('No direct script access allowed');


?>

<div class='row'>
    <div class='col-sm-10 col-sm-offset-1 content'>
        <div class='inner-content'>
            <div class='col-sm-12'>
                <?php $this->load->view('templates/notification', array(
            'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
            'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
            )); ?>
        </div>
        <h3>Vouchers</h3>
        <hr>
        <div class='paging-container' style='float:right'>
            <?php echo $pagination_links; ?>
        </div>
        <form class='form col-sm-3'>
            <label>Search By Code</label>
            <div class='input-group'>
                <input class='form-control' name='code' type='text' placeholder='Voucher Code'>
                <span class='input-group-btn'>
                    <button class='btn btn-primary' type='submit'>Go!</button>
                </span>
            </div>
        </form>
        <table class='table table-condensed'>
            <tr>
                <th></th>
                <th>Status</th>
                <th>Code</th>
                <th>Exp Type</th>
                <th>Starts</th>
                <th>Ends</th>
                <th>Discount</th>
                <th>Uses</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
            <?php foreach($vouchers as $voucher): ?>
                <tr>
                    <td>
                        <div class="dropdown">
                          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Actions
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="<?php echo base_url().'admin/vouchers/show/'.$voucher->id; ?>">View</a></li>
                            <li><a href="#" data-toggle='modal' data-target='#submission_edit_modal' data-id="<?php echo $voucher->id; ?>">Edit</a></li>
                          </ul>
                        </div>
                    </td>
                    <td>
                        <div class='toggle-container-centering'>
                            <?php if($voucher->status == 1): ?>
                                <div class='toggle-container toggle-on' data-id="<?php echo $voucher->id; ?>">
                                    <div class='toggle-ball'></div>
                                </div>
                            <?php else: ?>
                                <div class='toggle-container toggle-off' data-id="<?php echo $voucher->id; ?>">
                                    <div class='toggle-ball'></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo strtoupper($voucher->code); ?></td>
                    <td><?php echo $voucher->expiration; ?></td>
                    <td><?php echo date('D, M d', $voucher->starts_at); ?></td>
                    <td><?php echo date('D, M d', $voucher->ends_at); ?></td>
                    <td>
                        <?php if($voucher->discount_type == 'amount'): ?>
                            <?php echo '$'.number_format($voucher->value, 2); ?>
                        <?php else: ?>
                            <?php echo number_format($voucher->value, 2).'%'; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $voucher->times_used.' / '.$voucher->usage_limit; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', $voucher->created_at); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', $voucher->updated_at); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.toggle-container').click(function(e) {
            console.log($(this));
            var id = $(this).data('id');
            action = 'deactivate';
            var div = $(this);
            if(div.hasClass('toggle-off')) action = 'activate';
            $.ajax({
                method      : 'post',
                dataType    : 'json',
                url         : "<?php echo base_url().'admin/vouchers/'; ?>"+action+'/'+id,
                success     : function(response) {
                    console.log(response);
                    if(response.success)
                    {
                        if(action == 'deactivate')
                        {
                            div.removeClass('toggle-on').addClass('toggle-off');
                        }
                        else
                        {
                            div.addClass('toggle-on').removeClass('toggle-off');
                        }
                    }
                    else {
                        alert(response.error);
                    }
                }
            })
        });

    })
</script>
