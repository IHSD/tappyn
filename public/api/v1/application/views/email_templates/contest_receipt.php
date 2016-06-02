<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>

<!-- Start Email Content -->


<div class="invoice-box" style="max-width: 800px;margin: auto;padding: 30px;border: 1px solid #eee;box-shadow: 0 0 10px rgba(0, 0, 0, .15);font-size: 16px;line-height: 24px;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;color: #555;">
        <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
            <tr class="top">
                <td colspan="2" style="padding: 5px;vertical-align: top;">
                    <table style="width: 100%;line-height: inherit;text-align: left;">
                        <tr>
                            <td class="title" style="padding: 5px;vertical-align: top;padding-bottom: 20px;font-size: 25px;line-height: 25px;color: #333;">
                                Thank you for your purchase!
                            </td>

                            <td style="padding: 5px;vertical-align: top;text-align: right;padding-bottom: 20px;">
                                Contest #: <?php echo $contest->id; ?><br>
                                Created: <?php echo date('F d, Y', strtotime($contest->created_at)); ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td style="padding: 5px;vertical-align: top;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                    Payment Method
                </td>

                <td style="padding: 5px;vertical-align: top;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">

                </td>
            </tr>

            <tr class="details">
                <?php if($charge): ?>
                    <td style="padding: 5px;vertical-align: top;padding-bottom: 20px;">
                        <?php echo $charge->source->brand; ?> ending in <?php echo $charge->source->last4; ?>
                    </td>

                    <td style="padding: 5px;vertical-align: top;text-align: right;padding-bottom: 20px;">
                        $ <?php echo number_format(($charge->amount / 100), 2); ?>
                    </td>
                <?php else: ?>
                    <td style="padding: 5px;vertical-align: top;padding-bottom: 20px;">
                        No Payment Necessary
                    </td>
                    <td style="padding: 5px;vertical-align: top;text-align: right;padding-bottom: 20px;">
                        $ 0.00
                    </td>
                <?php endif; ?>
            </tr>
            <tr class="heading">
                <td style="padding: 5px;vertical-align: top;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                    Item
                </td>

                <td style="padding: 5px;vertical-align: top;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                    Price
                </td>
            </tr>

            <tr class="item">
                <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">
                    <?php echo ucfirst($contest->platform) ?> Contest
                </td>

                <td style="padding: 5px;vertical-align: top;text-align: right;border-bottom: 1px solid #eee;">
                    $99.00
                </td>
            </tr>

            <?php if(!$voucher): ?>
            <tr class="total">
                <td style="padding: 5px;vertical-align: top;"></td>

                <td style="padding: 5px;vertical-align: top;text-align: right;border-top: 2px solid #eee;font-weight: bold;">
                   Total: $99.00
                </td>
            </tr>
            <?php else: ?>
                <?php
                if($voucher->discount_type == 'percentage')
                {
                     $percentage = round($voucher->value / 100, 2);
                     $discount = round(99.00 * $value, 2);
                     $new_price = 99.00 - $discount;
                }
                else if($voucher->discount_type == 'amount')
                {
                    $discount = round($voucher->value, 2);
                    $new_price = 99.00 - $discount;
                }
                if($new_price < 0) $new_price = 00.00;
                ?>
                <tr class="item">
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">
                        Voucher <?php echo $voucher->code; ?>
                    </td>

                    <td style="padding: 5px;vertical-align: top;text-align: right;border-bottom: 1px solid #eee;">
                        - $ <?php echo number_format($discount, 2); ?>
                    </td>
                </tr>
                <tr class="total">
                    <td style="padding: 5px;vertical-align: top;">

                    </td>
                    <td style="padding: 5px;vertical-align: top;text-align: right;border-top: 2px solid #eee;font-weight: bold;">
                       Total: $ <?php echo number_format($new_price, 2); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <br>
<!-- End Email Content -->

<p style='text-align:left;margin:auto;width:600px'>
    Feel free to hit me up with any feedback or questions!
</p>
<br>
<p style='text-align:left;margin:auto;width:600px'>
  -Alek
  <br>
  Co-Founder Tappyn
    <br>
  (678)-367-1060
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
