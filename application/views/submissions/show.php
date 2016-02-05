<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<!-- Generate share button if users is owner of submission -->
<?php if((int)$submission->owner == $this->ion_auth->user()->row()->id): ?>
    <div class="fb-share-button" data-href="http://localhost/submissions/show/7" data-layout="button"></div>
<?php endif; ?>

<!-- Generate a select as winner button if campaign is over and user owns contest -->
<?php if((int)$submission->contest->owner == $this->ion_auth->user()->row()->id): ?>
<?php endif; ?>

<!-- If user is admin, allow to delete this submission -->
<?php if($this->ion_auth->in_group(1)): ?>
    <a href='/path/to/sub/delete'data-confirm='Are you sure?'>Delete this submission</a>
<?php endif; ?>


<script>
FB.ui({
  method: 'feed',
  link: 'https://developers.facebook.com/docs/',
  caption: 'An example caption',
}, function(response){});
</script>
