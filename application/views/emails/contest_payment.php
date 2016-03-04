<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * @param string $company Name of the company
 * @param object $contest Object containing contest metadata
 * @param object $charge Object containing payment metadata
 * @param object $voucher Object containing voucher if used
 * @param integer $eid  ID of the email for tracking purposes
*/
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

<p style='text-align:center;margin:auto;width:600px;border-bottom:2px solid #FF5E00'></p><br>

<h4 style='text-align:center;margin:auto;width:600px'>
    Contest details
</h4>
<br>
<table style='text-align:left;margin:auto;width:600px'>
    <tr>
        <td>Platform</td>
        <td><?php echo ucfirst($contest->platform); ?></td>
    </tr>
    <tr>
        <td>Objective</td>
        <td><?php echo ucfirst($contest->objective); ?></td>
    </tr>
    <tr>
        <td>Display Type</td>
        <td><?php echo ucfirst($contest->display_type); ?></td>
    </tr>
    <tr>
        <td>Start Time</td>
        <td><?php echo $contest->start_time; ?></td>
    </tr>
    <tr>
        <td>Stop Time</td>
        <td><?php echo $contest->stop_time; ?></td>
    </tr>
</table>
<br><hr style='text-align:center;margin:auto;width:600px;'><br>
<h4 style='text-align:center;margin:auto;width:600px'>
    Payment details
</h4>
<br>
<table style='text-align:left;margin:auto;width:600px'>
    <tr>
        <td>Price</td>
        <td>$99.99</td>
    </tr>
    <?php if($voucher): ?>
        <?php
            $amount = 9999;
            if($voucher->discount_type == 'amount') {
                $disc = $voucher->value;
            } else {
                $disc = ($amount * $voucher->value) / 100;
            }

        ?>

        <tr>
            <td>Voucher <?php echo $voucher->code; ?></td>
            <td>
                - $<?php echo $disc; ?>
            </td>
        </tr>
    <?php endif; ?>

    <?php if(!empty($charge)): ?>
        <tr>
            <td>Source</td>
            <td><?php echo $charge->source->brand; ?></td>
        </tr>
        <tr>
            <td>Last 4</td>
            <td><?php echo $charge->source->last4; ?></td>
        </tr>
        <tr>
            <td>Exp</td>
            <td><?php echo $charge->source->exp_month.' / '.$charge->source->exp_year; ?></td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>
                <?php if($voucher): ?>
                    <?php
                        $amount = 9999;
                        if($voucher->discount_type == 'amount') {
                            $amount = $amount - ($voucher->value * 100);
                        } else {
                            $amount = $amount - ($amount * $voucher->value);
                        }
                        echo '$'.$amount / 100;
                    ?>
                <?php else: ?>
                    $99.99
                <?php endif; ?>
            </td>
        </tr>
    <?php else: ?>
        No Payment Necessary!
    <?php endif; ?>

</table>
<br>
<p style='text-align:center;margin:auto;width:600px;border-bottom:2px solid #FF5E00'></p><br>
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
