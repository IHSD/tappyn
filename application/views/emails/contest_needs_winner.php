<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Template for a submission winning
 *
 * Sent after a company chooses a submission at contest end
 * @param string $company  Name of the company that owns the contest
 * @param string $contest_id  ID of the contest that has ended
 * @param integer $eid     ID of the email, for tracking purposes
 */

if(!isset($company) || !isset($contest_id))
{
   error_log("Creating email with missing data!");
   throw new Exception("Tried creating email with missing data");
}
?>

<!-- Start Template -->
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:center'>Hi <?php echo $company; ?>,</h2>

<p style='text-align:center;margin:auto;width:600px'>Phenomenal news. Your contest closed and it's time to pick your winner!</p><br>

<p style='text-align:center'>
    <a style='display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size: 18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo base_url().'#/submissions/'.$contest_id; ?>">
        Select Winner
    </a>
<p><br>
<p style='text-align:center;margin:auto;width:600px'>You have 3 days to decide, but the sonner you select your winner, the sonner your advertising game can change!</p><br>


<!-- Begin footer -->
<p style='margin:auto;width:600px;'>
    Let us know if you have any questions,
</p>
<br>
<p style='margin:auto;width:600px;'>
    Alek
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End footer -->
