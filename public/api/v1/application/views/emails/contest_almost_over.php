<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Template for a successful submission.
 *
 * Sent after every submission
 * @param string $company Name of the company who owns the contest
 * @param integer $eid     ID of the email, for tracking purposes
 */

if(!isset($company))
   {
       throw new Exception("Tried creating email with missing data");
   }
?>

<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>
<p style='text-align:center;margin:auto;width:600px'>
    You're submission just won the contest for <?php echo $company; ?>
</p>
<br>

<p style='text-align:center;margin:auto;width:600px'>
    We don't know what you're drinking, but send some to our PO Box.
</p>
<br>

<p style='text-align:center;margin:auto;width:600px'>
    Unless it's peach vodka. Then keep it far, far away from our mailbox.
</p>
<br>

<p style='text-align:center;margin:auto;width:600px'>
    Collect your winnings here <?php echo base_url().'#/dashboard'; ?>
</p>
<br>
<br>
<p style='margin:auto;width:600px;'>
    Hit reply with feedback or questions,
</p>
<br>
<p style='margin:auto;width:600px;'>
    Alek
    <br>
    <a href="<?php echo base_url(); ?>">www.fabel.us</a>
</p>
