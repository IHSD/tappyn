<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>


<!-- Start Template -->
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:left;margin:auto;width:600px;'>Hi <?php echo $cname; ?>,</h2>
<br>

<p style='text-align:left;margin:auto;width:600px'>Congratulations on picking your winning submission!</p><br>

<?php if(isset($headline)): ?>
    <p style='text-align:left;margin:auto;width:600px'><strong>Headline</strong></p>
    <p style='text-align:left;margin:auto;width:600px'><?php echo $headline; ?></p><br>
<?php endif; ?>
<?php if(isset($text)): ?>
    <p style='text-align:left;margin:auto;width:600px'><strong>Text</strong></p>
    <p style='text-align:left;margin:auto;width:600px'><?php echo $text; ?></p><br>
<?php endif; ?>

<p style='text-align:left;margin:auto;width:600px'><strong>Now it's time to put your content to work!</strong></p><br>

<?php $this->load->view('emails/templates/'.$platform); ?>

<br>

<!-- Begin footer -->
<p style='margin:auto;width:600px;'>
    Thanks
</p>
<br>
<p style='margin:auto;width:600px;'>
    Tappyn Team
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End footer -->
