<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 *
 * @param array   $contests Arroy of interesting contests
 * @param integer $eid ID of the email for tracking purposes
 */

if(!isset($contests) || empty($contests))
{
    throw new Exception("Invalid contests object array supplied for email");
}

?>

<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>
<br>
<p style='text-align:center;margin:auto;width:600px'>
    We just thought we'd take a minute to let you know <strong>the coolest companies ever</strong> just happened to launch on Tappyn
</p>
<br>

    <?php foreach($contests as $contest): ?>
        <p style='text-align:center;margin:auto;width:600px'>
            <strong><?php echo $contest['company']; ?></strong>&nbsp;<strong><?php echo $contest['description']; ?></strong>
        </p>
        <br>
    <?php endforeach; ?>

<p style='margin:auto;width:600px;'>
    Hit reply with feedback or questions,
</p>
<br>
<p style='margin:auto;width:600px;'>
    Alek
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
