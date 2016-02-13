<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php echo form_open("payouts/claim/{$payout->id}"); ?>
    <?php echo form_input(array('name' => 'source_id', 'type' => 'text')); ?>
    <?php echo form_submit("submit", "Claim Payout"); ?>
<?php echo form_close(); ?>
