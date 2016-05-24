<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('company');
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

<h4>Congratulations, you're submission won</h4>

<h5><?php echo $company->name; ?> chose your submission, so you're getting $50!</h5>

<p>To collect your payment, head to your <?php echo anchor('api/v1/analytics/click?'.http_build_query($query_string), 'dashboard'); ?> and just click claim! If you haven't yet,
    you'll need to set up an account, which takes only seconds. Not a bad hourly wage!</p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
