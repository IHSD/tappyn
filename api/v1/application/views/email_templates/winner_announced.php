<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('company');
foreach($requirements as $req)
{
    if(!isset($$req))
    {
        throw new Exception("Email data missing {$req}");
    }
}
$query_string['redirect'] = '#/dashboard';

?>


<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>

<!-- Start Email Content -->

<h5><?php echo $company->name; ?>'s contest has ended</h5>

<p>Unfortunately, your submission was'nt chosen. But remember, you get points for upvoting, and if you upvoted the winner, you got extra points</p>

<p>You can see who won <?php echo anchor('analytics/click?'.http_build_query($query_string), 'here'); ?></p>
<?php $query_string['redirect'] = '#/contests'; ?>
<p>Or you can see some of our <?php echo anchor('analytics/click?'.http_build_query($query_string), 'new contests'); ?> and take another shot!</p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>