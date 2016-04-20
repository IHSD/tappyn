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

<p>Congrats! Your Tappyn account has been successfully activated.</p>

<p>1) Running an ad contest is easy. Simply head to your <?php echo anchor('api/v1/analytics/click?'.http_build_query($query_string), 'dashboard'); ?> and hit start new contest.</p>

<p>2) Select which type of ad you'd like and who you plan on showing it to you</p>

<p>3) Launch your contest and watch submissions and votes roll in </p>

<p>4) Choose your favorite submission at the end of the contest and leave with an ad guaranteed to relate to your audience.</p>

<p>Got a question? I'm here to help(link to my e-mail) anytime </p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
