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

<h2 style='text-align:center;margin:auto;width:50%'>Welcome on board, Captain.</h2>
<br>
<p style='text-align:justify;margin:auto;width:50%'>Now that you have signed up, its time to set sail!</p><br>
<p style='text-align:justify;margin:auto;width:50%'>This isn't an adventure of pirates, sea monsters, or rum (well maybe some rum),</p><br>
<p style='text-align:justify;margin:auto;width:50%'>but we can offer you the chance at some big treasure.</p><br>
<p style='text-align:justify;margin:auto;width:50%'>No digging required</p><br><br>

<p style-'text-align:justify;margin:auto;width:50%'>To get started:</p><br>
<p style-'text-align:justify;margin:auto;width:50%'>1) Verify your email <?php echo anchor('analytics/click?'.http_build_query($query_string), 'here'); ?></p><br>
<p style-'text-align:justify;margin:auto;width:50%'>2) Sign in to Tappyn</p><br>
<p style-'text-align:justify;margin:auto;width:50%'>3) Hop on over to your personalized "See Contests" page</p><br>
<p style-'text-align:justify;margin:auto;width:50%'>4) Start winnind big payouts for being creative!</p><br>
<br>


<!-- End Email Content -->

<?php $this->load->view('email_templates/footer_austin'); ?>
