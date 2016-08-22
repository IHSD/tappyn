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

<p>Thanks for using Fabel, and congrats on picking an amazing user generated ad. </p>
<p>Your audience is going to love it!</p>

<p><strong>Details</strong></p>
<p><strong>Platform :</strong> <?php echo $contest->platform; ?></p>
<p><strong>Target Audience :</strong> <?php echo $contest->min_age; ?> - <?php echo $contest->max_age; ?> year olds in your specified gender category </p>
<br>
<p><strong>Ad Creative</strong></p>
<?php if(!is_null($submission->headline) && !$submission->headline == ''): ?>
    <p><strong>Headline :</strong> <?php echo $submission->headline; ?></p>
<?php endif; ?>
<?php if(!is_null($submission->text) && !$submission->text == ''): ?>
    <p><strong>Text :</strong> <?php echo $submission->text; ?></p>
<?php endif; ?>
<?php if(!is_null($submission->attachment)): ?>
    <p><strong>Ad:</strong> You'll find your chosen ads attached below</p>
<?php endif; ?>

<br>
<p>Looking for an ad for a different audience?</p>

<p><a href="https://fabel.us/launch">Launch a new one now!</a></p>

<!-- End Email Content -->

<p>
    Feel free to hit me up with any feedback or questions!
</p>
<br>
<p>
  -Alek
  <br>
  Co-Founder Fabel
    <br>
  (678)-367-1060
  <br>
    <a href="<?php echo base_url(); ?>">www.fabel.us</a>
</p>
