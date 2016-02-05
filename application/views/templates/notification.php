<?php if(isset($error) || isset($message)): ?>
    <div class='alert-container medium-6 medium-offset-3 small-12'> <!-- Container for all alert messages -->
        <?php if($error): ?>
            <div class='error-alert'>
                <?php echo $error; ?>
            </div>
        <?php elseif($message): ?>
            <!-- Message for successful requests -->
            <div class='message-alert'>
                <?php echo $message; ?>
            </div>

        <?php endif; ?>
    </div> <!-- Close alert-container -->
<?php endif; ?>
