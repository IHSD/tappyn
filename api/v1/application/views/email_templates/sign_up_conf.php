<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('uid', 'activation', 'query_string');
foreach($requirements as $req)
{
    if(!isset($$req))
    {
        throw new Exception("Email data missing {$req}");
    }
}
$query_string['redirect'] = 'auth/activate/'.$uid.'/'.$activation;

?>

<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>

<!-- Start Email Content -->

<h2 style='text-align:center;margin:auto;width:50%'>Thank you for registering with us!</h2>
<br>
<p style='text-align:justify;margin:auto;width:50%'>
	Feel free to look around a bit. But in order to join the squad and start creating content, we need you to verify your email <?php echo anchor('analytics/click?'.http_build_query($query_string), 'here'); ?>.
</p>
<br>
<p style='text-align:justify;margin:auto;width:50%'>
	Good luck!
</p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
