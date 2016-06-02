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
<p style='text-align:left;margin:auto;width:600px'>Your payout has successfully been processed!</p><br>
<p style='text-align:left;margin:auto;width:600px'>For your records:</p></br>
    <p style='text-align:left;margin:auto;width:600px'>Account ID  :<?php echo $payout->account_id; ?></p><br>
    <p style='text-align:left;margin:auto;width:600px'>Transfer ID :<?php echo $payout->transfer_id; ?></p><br>
    <p style='text-align:left;margin:auto;width:600px'>Amount      :$<?php echo number_format($payout->amount / 100, 2); ?></p><br>
<p style='text-align:left;margin:auto;width:600px'>Please allow 3 - 5 days for the payment to process. In the meantime, keep your winning streak going, and check out some more awesome campaigns <a href="<?php echo base_url('contests'); ?>">here</a>.</p>
<!-- End Email Content -->

<?php $this->load->view('email_templates/austin_footer'); ?>
