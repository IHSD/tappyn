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

<p>Hi <?php echo $company->name; ?>,</p>

<p>Congrats! Your contest has finished!.</p>

<p>Its now time to select one fantastic submission to use in your next ad campaign!</p>
<p>Please visit your <?php echo anchor('api/v1/analytics/click?'.http_build_query($query_string), 'dashboard'); ?> and pick your winner!</p>
<p>You can choose the one with the most upvotes, or just the one that speaks to you the most.</p>
<p>Let us know how the ad performs. We're alwaays trying to get companies the best content!</p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
