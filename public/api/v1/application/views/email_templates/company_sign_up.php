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

<p>Hi <?php echo $company->name; ?> Team,</p>

<p>Congrats! Your Fabel account has been successfully activated. We’re ecstatic to have you on board.</p>

<p>1) Running an ad campaign is easy. Simply head to your <?php echo anchor('api/v1/analytics/click?'.http_build_query($query_string), 'dashboard'); ?>, and hit "Launch New Campaign".</p>

<p>2) Select which type of ad you'd like and who you plan on showing it to.</p>

<p>3) Launch your campaign, and watch personalized ads roll in. </p>

<p>4) Review the ads you’d like to test and start receiving results.</p>

<p>5) Look at all your ads with their results and decide which one you’d like to own.</p>

<p>Got a question? I’m <a href="mailto:alek@fabel.us">here to help</a>, and answer any questions. My cell is below - feel free to call me with any questions or concerns.</p>

<p>
    -Alek
    <br>
    Co-Founder Fabel
    <br>
    (678)-367-1060
    <a href="<?php echo base_url(); ?>">www.fabel.us</a>
</p>
<!-- End Email Content -->
