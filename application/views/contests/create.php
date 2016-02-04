<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php echo form_open('contests/create'); ?>

<?php var_dump($error); ?>
<p>
    Time Length
    <?php echo form_dropdown('time_length', $options); ?>
</p>

<p>
    Submission Limit
    <?php echo form_dropdown('submission_limit', $limits); ?>
</p>

<p>
    Prize
    <?php echo form_dropdown('prize', $prizes); ?>
</p>
<p>
    Title
    <?php echo form_input('title'); ?>
</p>

<p>
    Objective
    <?php echo form_input('objective'); ?>
</p>

<p>
    Platform
    <?php echo form_dropdown('platform', $platforms); ?>
</p>

<p>
    <?php echo form_submit('submit', 'Submit'); ?>
</p>

<?php echo form_close(); ?>
