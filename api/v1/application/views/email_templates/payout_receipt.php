<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('payout');
foreach($requirements as $req)
{
    if(!isset($$req))
    {
        throw new Exception("Email data missing {$req}");
    }
}

?>

<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>


<!-- Start Email Content -->
<p>Your payout has successfully been processed!</p><br>
<p>For your records:</p></br>
    <p>Account ID  :<?php echo $payout->account_id; ?></p><br>
    <p>Transfer ID :<?php echo $payout->transfer_id; ?></p><br>
    <p>Amount      :$<?php echo number_format($payout->amount / 100, 2); ?></p><br>
<p>Please allow 3 - 5 days for the payment to process. In the meantime, check out some more contests <a href="<?php echo base_url('contests'); ?>">here</a></p>
<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
