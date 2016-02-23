<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class='innerpage'>
    <div class='row'>
        <div class='large-12 columns'>
            <h2>Payouts</h2>
            <div class='medium-6 medium-offset-3 small-12'>
                <?php $this->load->view('templates/notification', array(
            'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
            'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
            )); ?>
            </div>

            <table class='large-12 columns'>
                <tr>
                    <th>Actions</th>
                    <th>ID</th>
                    <th>Created At</th>
                    <th>Claimed At</th>
                    <th>Contest ID</th>
                    <th>Amount</th>
                    <th>Submission ID</th>
                    <th>Pending</th>
                    <th>Claimed</th>
                    <th>User ID</th>
                    <th>Account ID</th>
                    <th>Transfer ID</th>
                    <th>Account Type</th>
                    <th>Notes</th>
                </tr>
                <?php foreach($payouts as $payout): ?>
                    <tr>
                        <td>
                            <ul class="dropdown menu" data-dropdown-menu>
                            <li>
                              <a href="#">Actions</a>
                              <ul class="menu">
                                <li><a href="<?php echo base_url().'admin/payments/show/'.$payout->id; ?>">View</a></li>
                                <li><a href="<?php echo base_url().'admin/payments/edit/'.$payout->id; ?>">Edit</a></li>
                              </ul>
                            </li>
                          </ul>
                        </td>
                        <td><?php echo $payout->id; ?></td>
                        <td><?php echo $payout->created_at; ?></td>
                        <td><?php echo $payout->claimed_at; ?></td>
                        <td><?php echo $payout->contest_id; ?></td>
                        <td><?php echo $payout->amount; ?></td>
                        <td><?php echo $payout->submission_id; ?></td>
                        <td><?php echo $payout->pending; ?></td>
                        <td><?php echo $payout->claimed; ?></td>
                        <td><?php echo $payout->user_id; ?></td>
                        <td><?php echo $payout->account_id; ?></td>
                        <td><?php echo $payout->transfer_id; ?></td>
                        <td><?php echo $payout->account_type; ?></td>
                        <td><?php echo $payout->notes; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class='pagination-container'>
                <?php echo $pagination_links; ?>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function(e) {
    var getVars = getUrlVars();
    console.log(getVars);
    $('.sort_header').click(function(f) {
        getVars.sort_by = $(this).attr('id');
        if(getVars.sort_dir && getVars.sort_dir == 'desc')
        {
            getVars.sort_dir = 'asc';
        }
        else
        {
            getVars.sort_dir = 'desc';
        }

        getVars.per_page = 1;
        document.location.href = "<?php echo base_url().'admin/payments/index?'; ?>"+buildQuery(getVars);
    });

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(m,key,value) {
          vars[key] = value;
        });
        return vars;
    }

    function buildQuery(vars)
    {
        var params = [];
        for(var d in vars)
        {
            params.push(encodeURIComponent(d)+"="+encodeURIComponent(vars[d]));
        }
        return params.join('&');
    }
})
</script>
<?php $this->load->view('templates/footer'); ?>
