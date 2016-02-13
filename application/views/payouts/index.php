<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class="innerpage">
<div class='medium-6 medium-offset-3 small-12'>
    <?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
</div>
<?php function plur($count, $text){ return $count.(( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ));} ?>
<h2 class="text-center">Payouts</h2>
    <div class="browse-contest">
        <div class="row padding">
            <div class="browse-contest-content">
                <div class="margin-minus">
                    <?php foreach($payouts as $payout): ?>
                        <?php echo json_encode($payout); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
   <script>
 $(document).foundation();
 </script>
