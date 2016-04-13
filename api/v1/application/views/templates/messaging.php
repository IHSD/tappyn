<?php if(isset($error) || $this->session->flashdata('error') || isset($message) || $this->session->flashdata('message')): ?>
    <div class='alert-container medium-6'> <!-- Container for all alert messages -->
        <?php if(isset($error) || $this->session->flashdata('error')): ?>

            <!-- Alert for responses that result in error -->
            <div class='error-alert'>
                <?php echo isset($error) ? $error : $this->session->flashdata('error'); ?>
            </div>

        <?php else: ?>

            <!-- Message for successful requests -->
            <div class='message-alert'>
                <?php echo isset($message) ? $message : $this->session->flashdata('message'); ?>
            </div>

        <?php endif; ?>

    </div> <!-- Close alert-container -->
<?php endif; ?>
