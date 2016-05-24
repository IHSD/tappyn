<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('contest', 'submission');
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

<p>Thanks for using Tappyn and congrats on picking an amazing ad!
    Your audience is going to love it!</p>

<p><strong>Details</strong></p>
<p><strong>Platform :</strong> <?php echo $contest->platform; ?></p>
<p><strong>Objective :</strong> <?php echo $contest->objective; ?></p>
<br>
<p><strong>Creative</strong></p>
<?php if(!is_null($submission->headline) && !$submission->headline == ''): ?>
    <p><strong>Headline :</strong> <?php echo $submission->headline; ?></p>
<?php endif; ?>
<?php if(!is_null($submission->text) && !$submission->text == ''): ?>
    <p><strong>Text :</strong> <?php echo $submission->text; ?></p>
<?php endif; ?>
<?php if(!is_null($submission->attachment)): ?>
    <p><strong>Ad Image :</strong> You'll find your Ad image attached below</p>
<?php endif; ?>

<br>
<p>Looking for an ad for a different audience?</p>

<p><a href="https://tappyn.com/launch">Launch a new one now!</a></p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
