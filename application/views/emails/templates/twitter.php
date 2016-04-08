<p style='text-align:left;margin:auto;width:600px'><strong><u>Ad Creative</u></strong></p><br>
<?php if(!is_null($submission->headline) && $submission->headline != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><strong>Website Title :</strong> <?php echo $submission->headline; ?></p><br>
<?php endif; ?>
<?php if(!is_null($submission->text) && $submission->text != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><strong>Tweet Copy :</strong> <?php echo $submission->text; ?></p><br>
<?php endif; ?>
