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

<p>Congrats! Your Tappyn account has been successfully activated. We’re ecstatic to have you on board.</p>

<p>1) Running an ad campaign is easy. Simply head to your <?php echo anchor('api/v1/analytics/click?'.http_build_query($query_string), 'dashboard'); ?>, and hit "Launch New Campaign".</p>

<p>2) Select which type of ad you'd like and who you plan on showing it to.</p>

<p>3) Launch your campaign, and watch personalized ads roll in. </p>

<p>4) Input your testing budget, and check out each ad's Click Through Rate (CTR).</p>

<p>5)  Purchase your favorite ads and leave with a relevant copy guaranteed to connect with your audience.</p>

<p>Got a question? I’m <a href="mailto:alek@tappyn.com">here to help</a>, and answer any questions. My cell is below - feel free to call me with any questions or concerns.</p>

<p>
    -Alek
    <br>
    Co-Founder Tappyn
    <br>
    (678)-367-1060
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End Email Content -->
