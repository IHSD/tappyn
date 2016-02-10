<div class='medium-8 small-12 columns'>
	<div class="contest-content">
	    <span><?php echo $submission->text; ?></span>
	</div>
</div>
<?php $last_name = $submission->owner->last_name[0]; ?>
<div class='medium-4 small-12 columns'><h3 class='submission-owner-title'><?php echo (!is_null($submission->owner->first_name) ? $submission->owner->first_name.' '.ucfirst($last_name) : 'Anonymous'); ?></h3></div>
