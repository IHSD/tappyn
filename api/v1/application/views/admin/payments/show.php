<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class='innerpage'>
    <div class='row'>
        <div class='large-12 columns'>
            <h2>Payout</h2>
            <div class='medium-6 medium-offset-3 small-12'>
                <?php $this->load->view('templates/notification', array(
                    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                )); ?>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view('templates/footer'); ?>
