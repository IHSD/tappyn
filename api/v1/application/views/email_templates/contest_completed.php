<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('company', 'query_string');
foreach($requirements as $req)
{
    if(!isset($$req))
    {
        throw new Exception("Email data missing {$req}");
    }
}

$query_string['redirect'] = 'dashboard';

?>
<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>

<!-- Start Email Content -->

<p style='text-align:center;margin:auto;min-width:450px;width:50%'>Hi <?php echo $company->name; ?>,</p><br>

<p style='text-align:center;margin:auto;min-width:450px;width:50%'>We hope you’re doing well!</p><br>

<p style='text-align:center;margin:auto;min-width:450px;width:50%'>This is a friendly reminder that your contest recently closed and it’s time to select your favorite submission as a winner.</p><br>

<p style='text-align:center;margin:auto;min-width:450px;width:50%'>Writers in your contest have been anxiously waiting to find out if they have won. If you have questions, let us know. If you’re ready, select the winner
Below!</p><br>

<p style='text-align:center'>
    <a style='display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size: 18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo base_url().'dashboard'; ?>">
        Select Winner
    </a>
<p><br>

<p style='text-align:center;margin:auto;min-width:450px;width:50%'>Please note that if you don’t select your winner soon, the submission with the most upvotes will be announced the winner.. So don’t delay: select your favorite submission now, and keep the ball rolling!</p><br>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
