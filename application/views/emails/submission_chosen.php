<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Template for a submission winning
 *
 * Sent after a company chooses a submission at contest end
 * @param string $company  Name of the company that owns the contest
 * @param integer $eid     ID of the email, for tracking purposes
 */

if(!isset($company))
{
   error_log("Creating email with missing data!");
}
?>

<!-- Start Template -->
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:center'>BOOM. WHAM. LIFT OFF.</h2>

<p style='text-align:center;margin:auto;width:600px'>Your submission just won the contest for <?php echo $company; ?></p><br>
<p style='text-align:center;margin:auto;width:600px'>We don't know what you're drinking, but send some to our PO Box.</p><br>
<p style='text-align:center;margin:auto;width:600px'>Unlesss it's peach vodka. Then keep it far, far away from our mailbox.</p><br><br>
<p style='text-align:center;margin:auto;width:600px'>
    Collect your winnings <a href"<?php echo base_url().'#/payment'; ?>">here</a>
</p>
<br>

<!-- Begin footer -->
<p style='margin:auto;width:600px;'>
    Hit reply with feedback or questions,
</p>
<br>
<p style='margin:auto;width:600px;'>
    Alek
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End footer -->
