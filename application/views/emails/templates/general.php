<p style='text-align:left;margin:auto;width:600px'><strong><u>Ad Creative</u></strong></p><br>
<?php if(!is_null($submission->headline) && $submission->headline != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><strong>Headline :</strong> <?php echo $submission->headline; ?></p><br>
<?php endif; ?>
<?php if(!is_null($submission->link_explanation) && $submission->link_explanation != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><strong>Website Banner:</strong> <?php echo $submission->link_explanation; ?></p><br>
<?php endif; ?>
