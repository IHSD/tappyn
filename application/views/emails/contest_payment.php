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
    Hi <?php echo $company; ?>
</p>
<br>

<p style='text-align:center;margin:auto;width:600px'>
    Here is your receipt for the contest you launched;
</p>
<br>

<!-- COMPANY RECEIPT -->

<h4 style='text-align:center;margin:auto;width:600px;'>
    <?php echo $contest; ?>
</h4>

<!-- END COMPANY RECEIPT -->
<table>
    <tr>
        <td>Contest ID</td>
        <td><?php echo $cid; ?></td>
    </tr>
    <tr>
        <td>Contest Name</td>
        <td><?php echo $contest; ?></td>
    </tr>
    <tr>
        <td>Platform</td>
        <td><?php echo $platform; ?></td>
    </tr>
    <tr>
        <td>Objective</td>
        <td><?php echo $objective; ?></td>
    </tr>
    <tr>
        <td>Start Time</td>
        <td><?php echo $start_time; ?></td>
    </tr>
    <tr>
        <td>Stop Time</td>
        <td><?php echo $stop_time; ?></td>
    </tr>
</table>
<br>
<p style='margin:auto;width:600px;'>
    We'll let you know when submissions start rolling in!
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
