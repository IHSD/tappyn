<?php if($error): ?>
    <div class='alert alert-danger text-center'>
        <?php echo $error; ?>
    </div>
<?php elseif($message): ?>
    <div class='alert alert-success text-center'>
        <?php echo $message; ?>
    </div>
<?php endif; ?>
